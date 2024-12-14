<?php

namespace Core\Request;

class Request 
{
    protected string $url;
    protected string $method;
    protected string $path;
    protected array $queries = [];

    public function __construct()
    {
        $this->url = $this->sanitizeUrl($this->getCurrentUrl());
        $this->method = $this->sanitizeMethod($this->getRequestMethod());
        $this->path = $this->sanitizePath(urldecode(parse_url($this->url, PHP_URL_PATH)));
        $this->queries = $this->parseQueries(parse_url($this->url, PHP_URL_QUERY));
    }

    // Get the current URL
    protected function getCurrentUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $host = filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL);
        $uri = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);

        return $protocol . $host . $uri;
    }

    // Sanitize the URL
    protected function sanitizeUrl($url)
    {
        // Remove all illegal characters from a URL
        return filter_var($url, FILTER_SANITIZE_URL);
    }

    // Get the request method (GET, POST, etc.)
    protected function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    // Sanitize the request method
    protected function sanitizeMethod($method)
    {
        // Allow only uppercase alphabets
        return preg_replace('/[^A-Z]/', '', strtoupper($method));
    }

    // Sanitize the path to prevent path traversal and script injection
    protected function sanitizePath($path)
    {
        // Remove directory traversal attempts
        $path = preg_replace('/\.\.(\/|\\|%2e%2e%2f|%2e%2e%5c)/i', '', $path);
        
        // Optionally, allow specific characters
        $path = preg_replace('/[^\p{L}\p{N}\-\/]/u', '', $path); // Allow letters, numbers, dash, and slashes
        
        return $path;
    }

    // Parse and sanitize query parameters
    protected function parseQueries(?string $queryString): array
    {
        $queries = [];
        if ($queryString) {
            parse_str($queryString, $queries);
            // Optionally sanitize query parameters if needed
            array_walk_recursive($queries, function (&$value) {
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            });
        }
        return $queries;
    }

    // Public method to get the URL
    public function getUrl()
    {
        return $this->url; // URL may be encoded for safety
    }

    // Public method to get the path
    public function getPath()
    {
        return trim($this->path,"/"); // Path should be decoded for accurate route matching
    }

    // Public method to get the request method
    public function getMethod()
    {
        return $this->method;
    }

    // Public method to get query parameters
    public function getQueries(): array
    {
        return $this->queries;
    }
}
