<?php


class Mdl_project extends DT_CI_Model
{
	public function __construct()
	{
		parent::__construct();
		// Your own constructor code

	}

	public function deleteRecord($projectId)
	{
		$tables = array('tbl_project');
		$this->db->where_in('project_id',$projectId);
		$this->db->delete($tables);
		$ids = is_array($projectId) ? implode(',',$projectId) : $projectId;
		$response = array();
		if ($this->db->affected_rows()) {
			$response['success'] = "true";
		}

		return $response;
	}

	public function getProjectData($projectId)
	{
		$this->db->select("tp.project_id,tp.project_name,tp.description,tp.is_active");
		$this->db->from('tbl_project as tp');
		$this->db->where('tp.project_id', $projectId);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}

	public function getProjectInfoData($projectId)
	{
		$this->db->select("tp.project_id,tp.project_name,tp.description,tp.is_active");
		$this->db->from('tbl_project as tp');
		$this->db->where('tp.project_id', $projectId);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}

	function getProjectDD($data)
	{
		$this->db->select('tp.project_id as id,tp.project_name as text');
		$this->db->from('tbl_project as tp');
		if (isset($data['filter_param']) && $data['filter_param'] != '') {
			$this->db->like("tp.project_name", $data['filter_param'], 'both');
		}
		$query = $this->db->get();
		$result['result'] = $query->result_array();
		
		if (isset($data['all_project']) && $data['all_project'] == 'true') {
			$arr = array( 
			'id' => 'all',
			'text' => 'All Project'
			);
			array_unshift($result['result'] ,$arr);
		}

		return json_encode($result);
	}
	function getTeamProjectDD($data)
	{
		if($data['project_head'] == ""){
			$result['result'] = array ( array ( 'id' => 0 , 'text' => 'First Select Team Head...' ));
		}else{
			$this->db->select('tt.project_id_listing');
			$this->db->from('tbl_team as tt');
			$this->db->where('tt.emp_id', $data['project_head']);
			$query = $this->db->get();
			$queryData = $query->row_array();
			$project_ids = explode(",",$queryData['project_id_listing']);
			
			$this->db->select('tp.project_id as id,tp.project_name as text');
			$this->db->from('tbl_project as tp');
			if(isset($project_ids) && $project_ids != "")
				$this->db->where_in('tp.project_id', $project_ids);
			
			if (isset($data['filter_param']) && $data['filter_param'] != '') {
				$this->db->like("tp.project_name", $data['filter_param'], 'both');
			}
			$query = $this->db->get();
			$result['result'] = $query->result_array();
			
			if (isset($data['all_project']) && $data['all_project'] == 'true') {
				$arr = array( 
				'id' => 'all',
				'text' => 'All Project'
				);
				array_unshift($result['result'] ,$arr);
			}
		}

		return json_encode($result);
	}

}
