<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Requests\CreatePhotoRequest;
use Api\V1\Http\Requests\UpdatePhotoRequest;
use Core\Models\Photo;
use Core\DataProviders\Photo\Contracts\PhotoDataProvider;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Routing\Controller;

/**
 * Class PhotosController.
 *
 * @property Auth $auth
 * @property PhotoDataProvider photoDataProvider
 * @package Api\V1\Http\Controllers
 */
class PhotosController extends Controller
{
    /**
     * PhotosController constructor.
     *
     * @param Auth $auth
     * @param PhotoDataProvider $photoDataProvider
     */
    public function __construct(Auth $auth, PhotoDataProvider $photoDataProvider)
    {
        $this->auth = $auth;
        $this->photoDataProvider = $photoDataProvider;
    }

    /**
     * @apiVersion 1.0.0
     * @api {post} /v1/photos Create
     * @apiName Create
     * @apiGroup Photos
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-type multipart/form-data
     * @apiParam {File{1KB..20MB}=JPEG,PNG} file Photo file.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 201 Created
     * {
     *     "id": 1,
     *     "created_by_user_id" 1,
     *     "url": "http://path/to/photo/file",
     *     "avg_color": "#000000",
     *     "created_at": "2016-10-24 12:24:33",
     *     "updated_at": "2016-10-24 14:38:05",
     *     "exif": {
     *         "manufacturer": "Manufacturer Name",
     *         "model": "Model Number",
     *         "exposure_time": "1/160",
     *         "aperture": "f/11.0",
     *         "iso": 200,
     *         "taken_at": "2016-10-24 12:24:33"
     *     },
     *     "thumbnails": [
     *         "medium": {
     *             "url": "http://path/to/photo/thumbnail/medium_file"
     *             "width": 500,
     *             "height": 500
     *         },
     *         "large": {
     *              "url": "http://path/to/photo/thumbnail/large_file"
     *              "width": 1000,
     *              "height": 1000
     *         }
     *     ]
     * }
     */

    /**
     * Create a photo.
     *
     * @param CreatePhotoRequest $request
     * @return Photo
     */
    public function create(CreatePhotoRequest $request): Photo
    {
        $photo = new Photo;

        $photo->setCreatedByUserIdAttribute($this->auth->user()->id)
            ->setIsPublishedAttribute(false);

        $this->photoDataProvider->save($photo, $request->all(), ['with' => ['exif', 'thumbnails']]);

        return $photo;
    }

    /**
     * @apiVersion 1.0.0
     * @api {get} /v1/photos/:id Get
     * @apiName Get
     * @apiGroup Photos
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1..N}} :id Unique resource ID.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *     "id": 1,
     *     "created_by_user_id" 1,
     *     "url": "http://path/to/photo/file",
     *     "avg_color": "#000000",
     *     "created_at": "2016-10-24 12:24:33",
     *     "updated_at": "2016-10-24 14:38:05",
     *     "exif": {
     *         "manufacturer": "Manufacturer Name",
     *         "model": "Model Number",
     *         "exposure_time": "1/160",
     *         "aperture": "f/11.0",
     *         "iso": 200,
     *         "taken_at": "2016-10-24 12:24:33"
     *     },
     *     "thumbnails": [
     *         "medium": {
     *             "url": "http://path/to/photo/thumbnail/medium_file"
     *             "width": 500,
     *             "height": 500
     *         },
     *         "large": {
     *              "url": "http://path/to/photo/thumbnail/large_file"
     *              "width": 1000,
     *              "height": 1000
     *         }
     *     ]
     * }
     */

    /**
     * Get a photo.
     *
     * @param Photo $photo
     * @return Photo
     */
    public function get(Photo $photo): Photo
    {
        return $photo;
    }

    /**
     * @apiVersion 1.0.0
     * @api {post} /v1/photos/:id Update
     * @apiName Update
     * @apiGroup Photos
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-type multipart/form-data
     * @apiParam {Integer{1..N}} :id Unique resource ID.
     * @apiParam {File{1KB..20MB}=JPEG,PNG} file Photo file.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *     "id": 1,
     *     "created_by_user_id" 1,
     *     "url": "http://path/to/photo/file",
     *     "avg_color": "#000000",
     *     "created_at": "2016-10-24 12:24:33",
     *     "updated_at": "2016-10-24 14:38:05",
     *     "exif": {
     *         "manufacturer": "Manufacturer Name",
     *         "model": "Model Number",
     *         "exposure_time": "1/160",
     *         "aperture": "f/11.0",
     *         "iso": 200,
     *         "taken_at": "2016-10-24 12:24:33"
     *     },
     *     "thumbnails": [
     *         "medium": {
     *             "url": "http://path/to/photo/thumbnail/medium_file"
     *             "width": 500,
     *             "height": 500
     *         },
     *         "large": {
     *              "url": "http://path/to/photo/thumbnail/large_file"
     *              "width": 1000,
     *              "height": 1000
     *         }
     *     ]
     * }
     */

    /**
     * Update a photo.
     *
     * @param UpdatePhotoRequest $request
     * @param Photo $photo
     * @return Photo
     */
    public function update(UpdatePhotoRequest $request, Photo $photo): Photo
    {
        $this->photoDataProvider->save($photo, $request->all(), ['with' => ['exif', 'thumbnails']]);

        return $photo;
    }

    /**
     * @apiVersion 1.0.0
     * @api {delete} /v1/photos/:id Delete
     * @apiName Delete
     * @apiGroup Photos
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1..N}} :id Unique resource ID.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 204 No Content
     */

    /**
     * Delete a photo.
     *
     * @param Photo $photo
     * @return void
     */
    public function delete(Photo $photo)
    {
        $this->photoDataProvider->delete($photo);
    }
}
