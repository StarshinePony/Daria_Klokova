<?php
/**
 * @package     JCE
 * @subpackage  Admin
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @copyright   Copyright (c) 2009-2023 Ryan Demmer. All rights reserved
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Form\Form;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Language\Text;

class JFormFieldKeyValue extends FormField
{

    /**
     * The form field type.
     *
     * @var    string
     *
     * @since  2.8
     */
    protected $type = 'KeyValue';

    /**
     * Method to attach a JForm object to the field.
     *
     * @param   SimpleXMLElement  $element  The SimpleXMLElement object representing the <field /> tag for the form field object.
     * @param   mixed             $value    The form field value to validate.
     * @param   string            $group    The field name group control value. This acts as as an array container for the field.
     *                                      For example if the field has name="foo" and the group value is set to "bar" then the
     *                                      full field name would end up being "bar[foo]".
     *
     * @return  boolean  True on success.
     *
     * @since   2.8
     */
    public function setup(SimpleXMLElement $element, $value, $group = null)
    {
        $return = parent::setup($element, $value, $group);

        return $return;
    }

    /**
     * Method to get the field input markup.
     *
     * @return  string  The field input markup.
     *
     * @since   11.1
     */
    protected function getInput()
    {
        $values = $this->value;

        if (is_string($values) && !empty($values)) {
            $value = htmlspecialchars_decode($this->value);

            $values = json_decode($value, true);

            if (empty($values) && strpos($value, ':') !== false && strpos($value, '{') === false) {
                $values = array();

                foreach (explode(',', $value) as $item) {
                    $pair = explode(':', $item);

                    array_walk($pair, function (&$val) {
                        $val = trim($val, chr(0x22) . chr(0x27) . chr(0x38));
                    });

                    $values[] = array(
                        'name' => $pair[0],
                        'value' => $pair[1],
                    );
                }
            }
        }

        // default
        if (empty($values)) {
            $values = array(
                array(
                    'name' => '',
                    'value' => '',
                ),
            );
        }

        $subForm = new Form($this->name, array('control' => $this->formControl));
        $children = $this->element->children();

        $subForm->load($children);
        $subForm->setFields($children);

        $fields = $subForm->getFieldset();

        // And finaly build a main container
        $str = array();

        foreach ($values as $value) {
            $str[] = '<div class="form-field-repeatable-item wf-keyvalue">';
            $str[] = '  <div class="form-field-repeatable-item-group well well-small p-4 bg-light">';

            $n = 0;

            foreach ($fields as $field) {
                $field->element['multiple'] = true;

                $name = (string) $field->element['name'];

                $val = is_array($value) && isset($value[$name]) ? $value[$name] : '';

                // escape value
                $field->value = htmlspecialchars_decode($val);

                $field->setup($field->element, $field->value, $this->group);

                // reset id
                $field->id .= '_' . $n;

                // reset name
                $field->name = $name;

                $str[] = $field->renderField(array('description' => $field->description));

                $n++;
            }

            $str[] = '  </div>';

            $str[] = '  <div class="form-field-repeatable-item-control">';
            $str[] = '      <button class="btn btn-link form-field-repeatable-add" aria-label="' . Text::_('JGLOBAL_FIELD_ADD') . '"><i class="icon icon-plus pull-right float-right"></i></button>';
            $str[] = '      <button class="btn btn-link form-field-repeatable-remove" aria-label="' . Text::_('JGLOBAL_FIELD_REMOVE') . '"><i class="icon icon-trash pull-right float-right"></i></button>';
            $str[] = '  </div>';

            $str[] = '</div>';
        }

        if (!empty($this->value)) {
            $this->value = htmlspecialchars(json_encode($values));
        }

        $str[] = '<input type="hidden" name="' . $this->name . '" value="' . $this->value . '" />';

        return implode("", $str);
    }
}
