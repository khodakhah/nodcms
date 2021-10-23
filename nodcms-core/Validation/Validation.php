<?php
/*
 * NodCMS
 *
 * Copyright (c) 2015-2021.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *  @author     Mojtaba Khodakhah
 *  @copyright  2015-2021 Mojtaba Khodakhah
 *  @license    https://opensource.org/licenses/MIT	MIT License
 *  @link       https://nodcms.com
 *  @since      Version 3.1.0
 *  @filesource
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