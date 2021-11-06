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

namespace NodCMS\Core\Types;

class Link
{
    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $uri = "javascript:;";

    /**
     * Link constructor.
     * Fill variable from an array
     *
     * @param array|null $value
     */
    public function __construct(array $value = null)
    {
        if($value != null) {
            foreach($this as $key=>$item) {
                if(key_exists($key, $value)) {
                    $this->$key = $value;
                }
            }
        }
    }
}
