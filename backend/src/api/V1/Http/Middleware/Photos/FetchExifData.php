<?php

namespace Api\V1\Http\Middleware\Photos;

use Closure;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Lib\ExifFetcher\Contracts\ExifFetcher;
use Illuminate\Http\Request;

/**
 * Class FetchExifData.
 *
 * @property ExifFetcher exifFetcher
 * @package Api\V1\Http\Middleware\Photos
 */
class FetchExifData
{
    use ValidatesRequests;

    /**
     * FetchExifData constructor.
     *
     * @param ExifFetcher $exifFetcher
     */
    public function __construct(ExifFetcher $exifFetcher)
    {
        $this->exifFetcher = $exifFetcher;
    }

    /**
     * Validate request.
     *
     * @param Request $request
     */
    public function validateRequest(Request $request)
    {
        $this->validate($request, [
            'file' => ['required', 'file', 'image'],
        ]);
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->validateRequest($request);

        $exif = $this->exifFetcher->fetch($request->file('file')->getPathname());

        // Replace the temporary file name with the original one.
        $exif['FileName'] = $request->file('file')->getClientOriginalName();

        $request->merge(['exif' => ['data' => $exif]]);

        return $next($request);
    }
}
