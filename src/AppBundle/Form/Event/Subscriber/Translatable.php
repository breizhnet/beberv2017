<?php
/**
 * Created by PhpStorm.
 * User: laurent
 * Date: 25/12/2016
 * Time: 23:39
 */

namespace AppBundle\Form\Event\Subscriber;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

class Translatable implements EventSubscriberInterface
{
    /**
     * @var FormFactoryInterface
     */
    protected $factory;
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var ValidatorInterface
     */
    protected $validator;
    /**
     * @var array
     */
    protected $options = [];
    /**
     * @param FormFactoryInterface $factory
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     * @param array $options
     */
    public function __construct(
        FormFactoryInterface $factory,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        array $options
    ) {
        $this->em = $em;
        $this->options = $options;
        $this->factory = $factory;
        $this->validator = $validator;
    }
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that we want to listen on the form.pre_set_data
        // , form.post_data and form.bind_norm_data event
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::POST_SUBMIT  => 'postSubmit',
            FormEvents::SUBMIT       => 'submit'
        ];
    }
    /**
     * @param array $data
     *
     * @return array
     */
    private function bindTranslations($data)
    {
        // Small helper function to extract all Personal Translation
        // from the Entity for the field we are interested in
        // and combines it with the fields
        $collection = [];
        $available_translations = [];
        foreach ($data as $translation) {
            /* @var $translation AbstractPersonalTranslation */
            if (is_object($translation) &&
                strtolower($translation->getField()) == strtolower($this->options['field'])
            ) {
                $available_translations[strtolower($translation->getLocale())] = $translation;
            }
        }
        foreach ($this->getFieldNames() as $locale => $field_name) {
            if (isset($available_translations[strtolower($locale)])) {
                $translation = $available_translations[strtolower($locale) ];
            } else {
                $translation = $this->createPersonalTranslation($locale, $this->options['field'], null);
            }
            $collection[] = [
                'locale'      => $locale,
                'fieldName'   => $field_name,
                'translation' => $translation,
            ];
        }
        return $collection;
    }
    /**
     * @return array
     */
    private function getFieldNames()
    {
        //helper function to generate all field names in format:
        // '<locale>' => '<field>:<locale>'
        $collection = [];
        foreach ($this->options['locales'] as $locale) {
            $collection[$locale] = $this->options['field'] . ':' . $locale;
        }
        return $collection;
    }
    /**
     * @param string $locale
     * @param string $field
     * @param string $content
     *
     * @return mixed
     */
    private function createPersonalTranslation($locale, $field, $content)
    {
        // creates a new Personal Translation
        $class_name = $this->options['personal_translation'];
        return new $class_name($locale, $field, $content);
    }
    /**
     * @param FormEvent $event
     */
    public function submit(FormEvent $event)
    {
        // Validates the submitted form
        $form = $event->getForm();
        foreach($this->getFieldNames() as $locale => $field_name) {
            $content = $form->get($field_name)->getData();
            if (null === $content && in_array($locale, $this->options['required_locale'])) {
                $form->addError($this->getCannotBeBlankException($this->options['field'], $locale));
            } else {
                $errors = $this->validator->validate(
                    $this->createPersonalTranslation($locale, $field_name, $content),
                    null,
                    [sprintf('%s:%s', $this->options['field'], $locale)]
                );
                foreach ($errors as $error) {
                    $form->addError(new FormError($error->getMessage()));
                }
            }
        }
    }
    /**
     * @param string $field
     * @param string $locale
     *
     * @return FormError
     */
    public function getCannotBeBlankException($field, $locale)
    {
        return new FormError(sprintf('Field "%s" for locale "%s" cannot be blank', $field, $locale));
    }
    /**
     * @param FormEvent $event
     */
    public function postSubmit(FormEvent $event)
    {
        // if the form passed the validattion then set the corresponding Personal Translations
        $form = $event->getForm();
        $data = $form->getData();
        $entity = $form->getParent()->getData();
        foreach ($this->bindTranslations($data) as $binded) {
            $content = $form->get($binded['fieldName'])->getData();
            /* @var $translation AbstractPersonalTranslation */
            $translation = $binded['translation'];
            // set the submitted content
            $translation->setContent($content);
            // test if its new
            if ($translation->getId()) {
                //Delete the Personal Translation if its empty
                if (null === $content && $this->options['remove_empty']) {
                    $data->removeElement($translation);
                    if ($this->options['entity_manager_removal']) {
                        $this->em->remove($translation);
                    }
                }
            } elseif (null !== $content) {
                // add it to entity
                $entity->addTranslation($translation);
                if (! $data->contains($translation)) {
                    $data->add($translation);
                }
            }
        }
        // remove string elements from "translations", we need only objects
        foreach ($data as $rec) {
            if (! is_object($rec)){
                $data->removeElement($rec);
            }
        }
    }
    /**
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        // Builds the custom 'form' based on the provided locales
        $data = $event->getData();
        $form = $event->getForm();
        // During form creation setData() is called with null as an argument
        // by the FormBuilder constructor. We're only concerned with when
        // setData is called with an actual Entity object in it (whether new,
        // or fetched with Doctrine). This if statement let's us skip right
        // over the null condition.
        if (null === $data) {
            return;
        }
        foreach ($this->bindTranslations($data) as $binded) {
            /* @var $translation AbstractPersonalTranslation */
            $translation = $binded['translation'];
            $form->add($this->factory->createNamed(
                $binded['fieldName'],
                $this->options['widget'],
                $translation->getContent(),
                [
                    'auto_initialize'=> false,
                    'label' => $binded['locale'],
                    'required' => in_array($binded['locale'], $this->options['required_locale']),
                    'property_path' => null,
                    'attr' => $this->options['attr']
                ]
            ));
        }
    }
}