<?php
/*
 *  This file is part of NodCMS.
 *
 *  (c) Mojtaba Khodakhah <info@nodcms.com>
 *  https://nodcms.com
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 */

namespace NodCMS\Core\Validation;


class Validation extends \CodeIgniter\Validation\Validation
{
    protected $latestErrorMessage;

    /**
     * @inheritDoc
     * Put the message from this method to a local variable.
     *
     * @param string $rule
     * @param string $field
     * @param string|null $label
     * @param string|null $param
     * @param string|null $value
     * @return string
     */
    protected function getErrorMessage(string $rule, string $field, string $label = null, string $param = null, string $value = null): string
    {
        // set the latest error message
        $this->latestErrorMessage = parent::getErrorMessage($rule, $field, $label, $param, $value);
        return $this->latestErrorMessage;
    }

    /**
     * @inheritDoc
     * Customize the method to replace {field} and {value} variable on the error messages that have been set in
     * a rule method.
     *
     * @param string $field
     * @param string|null $label
     * @param array|string $value
     * @param null $rules
     * @param array $data
     * @return bool
     */
    protected function processRules(string $field, string $label = null, $value, $rules = null, ?array $data = null): bool
    {
        // clean the latest error message up
        $this->latestErrorMessage = null;
        // execute customize only if original method found an error
        if(!parent::processRules($field, $label, $value, $rules, $data)) {

            // The error message from the original method
            $message = $this->errors[$field];
            /*
             * If the error from the original method is not equal with latest error message, that means
             * "getErrorMessage()" has not been executed. So this is a message that came form rule methods.
             */
            if($message != $this->latestErrorMessage) {
                $message = str_replace('{field}', empty($label) ? $field : lang($label), $this->errors[$field]);
                $this->errors[$field] = str_replace('{value}', $value, $message);
            }
            return false;
        }
        return true;
    }
}
