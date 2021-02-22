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
 *  @since      Version 3.0.0
 *  @filesource
 *
 */

namespace NodCMS\Core\Response;

use Config\Services;

class Response
{
    /**
     * @var ResponseType
     */
    protected $type;

    protected $message;

    protected $url;

    protected $data;

    /**
     * Response constructor.
     *
     * @param ResponseType $type
     */
    public function __construct(ResponseType $type)
    {
        $this->type = $type;
    }

    /**
     * Set the error message.
     *
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * Set a url to redirect
     *
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * Set additional data to add on a json response
     *
     * @param $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Returns response result
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     */
    public function get()
    {
        $request = Services::request();
        if($request->isAJAX()){
            $data = array(
                "status"=>$this->type->status,
                "url"=>$this->url,
            );
            if($this->message!=null)
                $data[$this->type->messageVar] = $this->message;

            if(!empty($this->data))
                $data["data"] = $this->data;

            return json_encode($data);
        }

        if($this->message!=null) {
            $session = Services::session();
            $session->setFlashdata($this->type->status, $this->message);
        }
        return redirect()->to($this->url);
    }
}