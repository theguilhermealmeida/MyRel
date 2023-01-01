<?php

namespace App\Http\Controllers;

use App\Models\Relationship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RelationshipController extends Controller
{
        /**
     * Shows all the relationships of the loged in user.
     *
     * @return Response
     */
    public function show()
    {
        if (!Auth::check()) return redirect()->back();
        $pending_relationships = Auth::user()->relationships2()->where('state', 'pending')->orderBy('id')->get();

        $friends = Auth::user()->relationships()->where('state', 'accepted')->where('type', 'Friends')->orderBy('id')->get();
        $friends = $friends->merge(Auth::user()->relationships2()->where('state', 'accepted')->where('type', 'Friends')->orderBy('id')->get());

        $close_friends = Auth::user()->relationships()->where('state', 'accepted')->where('type', 'Close Friends')->orderBy('id')->get();
        $close_friends = $close_friends->merge(Auth::user()->relationships2()->where('state', 'accepted')->where('type', 'Close Friends')->orderBy('id')->get());

        $family = Auth::user()->relationships()->where('state', 'accepted')->where('type', 'Family')->orderBy('id')->get();
        $family = $family->merge(Auth::user()->relationships2()->where('state', 'accepted')->where('type', 'Family')->orderBy('id')->get());

        return view('pages.relationships', ['pending_relationships' => $pending_relationships, 'friends' => $friends, 'close_friends' => $close_friends, 'family' => $family]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $relationship = new Relationship();

        $relationship->user_id = Auth::user()->id;
        $relationship->related_id = $request->id;

        $relationship->type = $request->input('type');        
        $relationship->state = 'pending';
        $relationship->save();

        return redirect()->back(); 
    }


    /**
     * Accept a pending relationship request. 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function accept(Request $request)
    {

        $relationship = Relationship::find($request->id);
        $relationship->state = 'accepted';
        $relationship->save();

        return redirect()->back();    
    }

    /**
     * Delete a pending/accepted relationship. 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $relationship = Relationship::find($request->id);
        $relationship->delete();

        return redirect()->back();    
    }


}
