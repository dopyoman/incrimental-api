<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Lesson;
use Illuminate\Http\Response;

class LessonsController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //This is not a really good way to do this.
        //the main reason is that we aren't molding our data to be
        //more specific to the use case. Its just a dump of our database.
        //with very little other info.

        //1. All is bad
        //2. No way to attach meta data.
        //3. Linking db structure to the API output (things like password fields would be shown)
        //4. No way to signal headers/response codes.


        //Added eager loading of the authors from the users table.
        $lessons = Lesson::with('author')->get();

        return response()->json([
            'data' => $this->transformCollection($lessons)
        ],
            200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lesson = Lesson::find($id)->with('author')->first();

        /*Preforming a check to see if id exists in db.*/
        if ( ! $lesson)
        {
            return response()->json([
                'error' => 'Lesson does not exist'
            ],
                404);
        }

        /*If check doesn't fail we send out the data*/

        return response()->json([
            'data' => $this->transform($lesson)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * Transforms a collection of lessons to an array
     *
     * @param $lessons
     * @return array
     */
    public function transformCollection($lessons)
    {
        /*Instead of passing an anonymous function we are now telling it to us a function called transform
        and were passing each lesson to this.transform*/

        /*Not sure why this array method call works. But kind of cool but confusing Use call back functions instead
                    function ($lesson)
                    {
                        return $this->transform($lesson);
                    }
        */
        return array_map([$this, 'transform'], $lessons->toArray());
    }


    private function transform($lesson)
    {

        return [
            'title'  => $lesson['title'],
            'body'   => $lesson['body'],
            'active' => $lesson['someBol'],
            'author' => $lesson['author']['name']
        ];
    }
}
