<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupeRequest;

use App\Models\Groupe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GroupeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function index(Request $request) {
		$groupes = Groupe::sortable()
			->resetPaginate($request->only(['search']))
			->orderBy('name')
			->paginate(20);

		return view('groupes.index', compact('groupes'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		$groupe = new Groupe();
		return view('groupes.create', compact('groupe'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param GroupeRequest $request
	 * @param Groupe $groupe
	 * @return Response
	 */
	public function store(GroupeRequest $request, Groupe $groupe) {
    	$data = $request->all();
		$groupe->fill($data);

		if ($groupe->save()){
			Session::flash('success', __('groupes.created'));
		}else{
			Session::flash('error', __('commun.global error'));
		}
        return redirect()->route('groupes.index');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  Groupe  $groupe
	 * @return Response
	 */
	public function view(Groupe $groupe) {
		return view('groupes.view', compact('groupe'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  Groupe $groupe
	 * @return Response
	 */
	public function edit(Groupe $groupe) {
		return view('groupes.edit', compact('groupe'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Groupe $groupe
	 * @param GroupeRequest $request
	 * @return Response
	 */
	public function update(GroupeRequest $request, Groupe $groupe) {
		$data = $request->all();

		if ($groupe->update($data)){
			Session::flash('success', __('groupes.updated'));
            return redirect()->route('groupes.index');
		}
		Session::flash('error', __('commun.global error'));
		return $this->edit($groupe);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Groupe $groupe
	 * @return Response
	 * @throws \Exception
	 */
	public function delete(Groupe $groupe) {
		if ($groupe->delete()) {
			Session::flash('success', __('groupes.deleted'));
		} else {
			Session::flash('error', __('commun.global error'));
		}
		return redirect()->route('groupes.index');
	}

}
