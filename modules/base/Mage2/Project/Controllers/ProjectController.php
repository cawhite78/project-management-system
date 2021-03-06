<?php

namespace Mage2\Project\Controllers;

use Mage2\System\Controllers\Controller;
use Mage2\Project\Models\Project;
use Mage2\User\Models\AdminUser;
use Mage2\Project\Requests\ProjectRequest;
use Mage2\Framework\DataGrid\DataGrid;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $projects = Project::paginate(10);
        $project = new Project();
        $dataGrid = DataGrid::make($project);

        $dataGrid->addColumn(DataGrid::textColumn('name', 'Project Name', ['sortable' => 'asc']));

        $dataGrid->addColumn(DataGrid::textColumn('description', 'Project Description'));

        if (Gate::allows('hasPermission', [AdminUser::class, "project.edit"])) {
            $dataGrid->addColumn(DataGrid::linkColumn('edit', 'Edit', function ($row) {
                        return "<a href='" . route('project.edit', $row->id) . "'>Edit</a>";
                    }));
        }


        if (Gate::allows('hasPermission', [AdminUser::class, "project.destroy"])) {
            $dataGrid->addColumn(DataGrid::linkColumn('destroy', 'Destroy', function ($row) {
                        return "<form method='post' action='" . route('project.destroy', $row->id) . "'>" .
                                "<input type='hidden' name='_method' value='delete'/>" .
                                csrf_field() .
                                '<a href="#" onclick="jQuery(this).parents(\'form:first\').submit()">Destroy</a>' .
                                "</form>";
                    }));
        }
        if (Gate::allows('hasPermission', [AdminUser::class, "project.show"])) {
            $dataGrid->addColumn(DataGrid::linkColumn('show', 'Show', function ($row) {
                        return "<a href='" . route('project.show', $row->id) . "'>Show</a>";
                    }));
        }
        return view('project.project.index')
                        ->with('dataGrid', $dataGrid)
        ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('project.project.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Mage2\Project\Requests\ProjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request) {

        try {
            $project = Project::create($request->all());
            $this->_saveContactProject($project, $request->get('contact_project'));
        } catch (Exception $ex) {
            new \Exception('Error while updating project' . $ex->getMessage());
        }
        return redirect()->route('project.index')->with('notificationText', 'Project Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $project = Project::findorfail($id);
        return view('project.project.show')->with('project', $project);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $project = Project::findorfail($id);
        return view('project.project.edit')->with('project', $project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Mage2\Project\Requests\ProjectRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectRequest $request, $id) {


        $project = Project::findorfail($id);
        try {
            $project->update($request->all());
            $this->_saveContactProject($project, $request->get('contact_project'));
        } catch (Exception $ex) {
            new \Exception('Error while updating project' . $ex->getMessage());
        }

        return redirect()->route('project.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        Project::destroy($id);

        return redirect()->route('project.index');
    }

    /**
     * 
     * Sync the PRojects contacts
     */
    private function _saveContactProject(Project $project, $contacts) {

        try {
            $project->contacts()->sync($contacts);
        } catch (\Exception $ex) {
            new \Exception('Error while Sync contacts project' . $ex->getMessage());
        }

        return true;
    }

}
