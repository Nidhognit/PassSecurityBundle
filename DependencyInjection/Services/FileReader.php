<?php

namespace Nidhognit\PassSecurityBundle\DependencyInjection\Services;

class FileReader implements InterfaceReader
{
    /** @var string */
    protected $fileName;

    protected $file;
    /** @var null|integer */
    protected $number = null;

    /**
     * FileReader constructor.
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->fileName = $options['file'];
        $this->readOptions($options);
    }

    /**
     * @param string $password
     * @param null   $limit
     * @return int|null
     */
    public function findByPassword($password, $limit = null)
    {
        $this->openFile();
        $this->readFromFile($password, $limit);
        $this->closeFile();

        return $this->number;
    }

    public function openFile()
    {
        $this->file = fopen($this->fileName, "r");
    }

    protected function readFromFile($password, $limit)
    {
        $row = 1;
        while (($line = fgets($this->file)) !== false) {

            if ($password === trim($line)) {
                $this->number = $row;
                break;
            }

            ++$row;
            if ($limit && $row > $limit) {
                break;
            }
        }

    }

    public function closeFile()
    {
        fclose($this->file);
    }

    private function readOptions(&$options)
    {
        if (file_exists($options['file'])) {
            if (pathinfo($options['file'], PATHINFO_EXTENSION) === 'txt') {
                $this->fileName = $options['file'];
                return true;
            }
        }

        throw new \InvalidArgumentException('File in variable "file" must be with "txt" extension');
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }
}