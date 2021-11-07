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

/**
 * Class FormatRules
 * This class has been created to reset/customize some CodeIgniter original rules.
 *
 * @package NodCMS\Core\Validation
 */
class FormatRules extends \CodeIgniter\Validation\FormatRules
{
    /**
     * Reset the original rules
     *
     * @param string|null $str
     * @return bool
     */
    public function valid_url(string $str = null): bool
    {
        // Ignore empty values
        if(empty($str))
            return true;

        return parent::valid_url($str);
    }
}
