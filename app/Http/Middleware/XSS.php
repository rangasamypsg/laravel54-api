<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class XSS
{

    protected $trim = true;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
     public function handle(Request $request, Closure $next) {

        if (!in_array(strtolower($request->method()), ['put', 'post'])) {
            return $next($request);
        }

        $input = $request->all();

        array_walk_recursive($input, function(&$input) {
            $input = strip_tags($input);
            
        });
       
        array_walk_recursive($input, function (&$item) {
            $item = trim($item);
            $item = ($item == "") ? null : $item;
        });
        
        $request->merge($input);

        return $next($request);

    }

    /**
     * Get trimmed input
     *
     * @param array $input
     *
     * @return array
     */
    public function getTrimmedInput(array $input)
    {
        if ($this->trim) {
            array_walk_recursive($input, function (&$item, $key) {
 
                if (is_string($item) && !str_contains($key, 'password')) {
                    $item = trim($item);
                }
            });
        }
 
        return $input;
    }
    
}
