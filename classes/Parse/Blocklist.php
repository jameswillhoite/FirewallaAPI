<?php

namespace Parse;

class Blocklist
{
    protected string $file;

    public function __construct(string $file = null)
    {
        if($file)
        {
            $this->setFile($file);
        }
    }

    /**
     * @param string $file file path or url
     * @return $this
     */
    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Parse the File|stream
     * @return array
     */
    public function parseFile(): array
    {
        $list = [];
        $handle = null;
        $ignoreRegex = "/^(127\.0|0\.0\.)/";

        try {
            $handle = fopen($this->file, "r");

            while(($line = fgets($handle)) !== false)
            {
                if(strpos($line, '#') === 0)
                {
                    continue;
                }

                preg_match("/([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}(\/[0-9]{1,2})?)/", $line, $matches);

                $ip_address = $matches[1] ?? "";

                if(!empty($ip_address))
                {
                    $line = str_replace($ip_address, '', $line);
                }

                preg_match("/((?:www\\.)?[-a-zA-Z0-9@:%._\\+~#=]{1,256}\\.[a-zA-Z0-9()]{1,6}\\b(?:[-a-zA-Z0-9()@:%_\\+.~#?&\\/=]*))/", $line, $matches);

                $url = $matches[1] ?? "";

                if(strlen($ip_address) > 0 && !preg_match($ignoreRegex, $ip_address))
                {
                    $list[] = $ip_address;
                }

                if(!empty($url))
                {
                    $list[] = $url;
                }

            }

            return $list;

        } catch (\Exception $e)
        {
            return [];
        } finally {
            if($handle)
            {
                fclose($handle);
            }
        }

    }
}