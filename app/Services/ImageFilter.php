<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2017/11/3
 * Time: 11:03
 */

namespace App\Services;


use App\Events\FilterImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageFilter
{

    /**
     * @var string
     */
    protected $wd;

    /**
     * @var string
     */
    protected $input;

    /**
     * @var string
     */
    protected $filter = '';

    /**
     * @var array
     */
    protected $param = [];

    /**
     * @var bool
     */
    protected $thumbnail = false;

    /**
     * ImageFilter constructor.
     * @param string $wd
     * @param UploadedFile|null $input
     * @param string $filter
     * @param array $param
     * @param bool $thumbnail
     */
    public function __construct($wd, $input = null, $filter = '', array $param = [], $thumbnail = false)
    {
        $this->wd = $wd;
        if ($input) {
            $this->input = 'origin.' . $input->clientExtension();
            $input->storePubliclyAs($this->getWorkDirectory(), $this->input);
        } else {
            $this->input = $this->getOrigin();
        }
        $this->filter = strtolower($filter);
        $this->param = $param;
        $this->thumbnail = $thumbnail;
    }

    public function run()
    {
        event(new FilterImage($this));
    }

    /**
     * @param string $filter
     * @return array
     */
    public function getResult($filter = '')
    {
        $dir = $this->getOutputDirectoryRealPath();
        $dirr = opendir($dir);
        if ($filter) {
            while (($file = readdir($dirr)) !== false) {
                if (starts_with($file, "$filter.")) {
                    $url = $url = $this->getFileUrl($file);
                    return [$filter => $url];
                }
            }
        } else {
            $out = [];
            while (($file = readdir($dirr)) !== false) {
                if ($file == '.' || $file == '..') {
                    continue;
                }
                if (is_file($dir . DIRECTORY_SEPARATOR . $file)) {
                    $f = substr($file, 0, strrpos($file, '.'));
                    $url = $this->getFileUrl($file);
                    $out[$f] = $url;
                }
            }
            return $out;
        }
        return [];
    }

    /**
     * @param $file
     * @return string
     */
    public function getFileUrl($file)
    {
        if ($this->thumbnail) {
            $d = implode('/', ['public', 'images', $this->wd, 'thumbnail', $file]);
        } else {
            $d = implode('/', ['public', 'images', $this->wd, $file]);
        }
        return Storage::url($d);
    }

    /**
     * @return string
     */
    public function getInputPath()
    {
        return $this->getWorkDirectory() . DIRECTORY_SEPARATOR . $this->input;
    }

    /**
     * @return string
     */
    public function getInputRealPath()
    {
        return Storage::path($this->getInputPath());
    }

    /**
     * @return string
     */
    public function getOutputDirectory()
    {
        if ($this->thumbnail) {
            $d = $this->getWorkDirectory() . DIRECTORY_SEPARATOR . 'thumbnail';
        } else {
            $d = $this->getWorkDirectory();
        }
        if (!Storage::exists($d)) {
            Storage::makeDirectory($d);
        }
        return $d;
    }

    /**
     * @return string
     */
    public function getOutputDirectoryRealPath()
    {
        return Storage::path($this->getOutputDirectory());
    }

    /**
     * @return string
     */
    public function getWorkDirectory()
    {
        return implode(DIRECTORY_SEPARATOR, ['public', 'images', $this->wd]);
    }

    /**
     * @return string
     */
    public function getWorkDirectoryRealPath()
    {
        return Storage::path($this->getWorkDirectory());
    }

    /**
     * @return string
     */
    public function getWd()
    {
        return $this->wd;
    }

    /**
     * @return string
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return string
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param string $filter
     * @return ImageFilter
     */
    public function setFilter(string $filter)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * @return array
     */
    public function getParam()
    {
        return $this->param;
    }

    /**
     * @param array $param
     * @return ImageFilter
     */
    public function setParam(array $param)
    {
        $this->param = $param;
        return $this;
    }

    /**
     * @return bool
     */
    public function isThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @param bool $thumbnail
     * @return ImageFilter
     */
    public function setThumbnail(bool $thumbnail)
    {
        $this->thumbnail = $thumbnail;
        return $this;
    }

    /**
     * @return string
     */
    protected function getOrigin()
    {
        $dir = opendir($this->getWorkDirectoryRealPath());
        while(($file = readdir($dir)) !== false) {
            if (substr($file, 0, 7) == 'origin.') {
                return $file;
            }
        }
        return '';
    }

}