<?php

namespace App\Http\Controllers;

use App\Author;
use App\traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller;

class AuthorsController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index() :JsonResponse
    {
        $authors = Author::all();

        return $this->successResponse($authors, Response::HTTP_OK);
    }

    public function store(Request $request) :JsonResponse
    {
        $rules = [
            'name'    => 'required|max:255',
            'gender'  => 'required|max:255|in:male,female',
            'country' => 'required|max:255',
        ];
        $this->validate($request, $rules);
        $author = Author::create($request->all());

        return $this->successResponse($author, Response::HTTP_CREATED);
    }

    public function show($author) :JsonResponse
    {
        $author = Author::findOrFail($author);

        return $this->successResponse($author);
    }

    public function update(Request $request, $author) :JsonResponse
    {
        $rules = [
            'name'    => 'max:255',
            'gender'  => 'max:255|in:male,female',
            'country' => 'max:255',
        ];

        $this->validate($request, $rules);
        $author = Author::findOrFail($author);
        $author->fill($request->all());

        if ($author->isClean()) {
            return $this->errorResponse('At least one value should change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $author->save();

        return $this->successResponse($author, Response::HTTP_CREATED);
    }

    public function destroy($author) :JsonResponse
    {
        $author = Author::findOrFail($author);
        $author->delete();
        return $this->successResponse($author);
    }
}
