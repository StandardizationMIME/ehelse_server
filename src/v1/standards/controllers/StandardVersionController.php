<?php

require_once __DIR__.'/../../responses/ResponseController.php';
require_once __DIR__.'/../../dbmappers/StandardVersionDBMapper.php';
require_once __DIR__.'/../../dbmappers/DocumentVersionDBMapper.php';
require_once __DIR__.'/../../dbmappers/DocumentVersionTargetGroupDBMapper.php';
require_once __DIR__.'/../../dbmappers/TargetGroupDBMapper.php';
require_once __DIR__.'/../../dbmappers/LinkDBMapper.php';
require_once __DIR__.'/../../dbmappers/LinkTypeDBMapper.php';
require_once __DIR__.'/../../dbmappers/StatusDBMapper.php';

class StandardVersionController extends ResponseController
{

    public function __construct($path, $method, $body, $standard_id)
    {
        $this->path = $path;
        $this->method = $method;
        $this->body = $body;

        if(empty($this->path)){

        }elseif(is_numeric($this->path[0])){
            $this->id = $this->path[0];
        }else{
            $this->controller = new DescriptionController();
        }
    }


    protected function create()
    {

        // TODO: must look at this
        // Only creating new standard version with empty targetGroup, links, fields

        // Status
        //$status_mapper = new StatusDBMapper();
        $status_id = 1;    // TODO: not in API desc.

        // DocumentVersion
        $document_version_mapper = new DocumentVersionDBMapper();
        $document_version_id = $document_version_mapper->add(new DocumentVersion(null, null, $status_id));

        // StandardVersion
        $standard_version_mapper = new StandardVersionDBMapper();
        $standard_version_id = $standard_version_mapper->add(new StandardVersion(null, null,
            $this->body['standardId'],
            $document_version_id,   // $this->body['document_version'],
            null)); // $this->body['comment']));        // TODO: needs update when API desc. is updated
        if ($standard_version_id instanceof DBError) {
            return new ErrorResponse($standard_version_id);
        }

        $standard_version = $standard_version_mapper->getById($standard_version_id);

        $response = array();
        $response['id'] = $standard_version->getId();
        $response['standardId'] = $standard_version->getStandardId();
        $response['targetGroups'] = $this->getTargetGroups($standard_version->getId());
        $response['links'] = $this->getLinks($standard_version->getDocumentVersionId());
        $response['fields'] = $this->getFields();

        return new Response(json_encode($response, JSON_PRETTY_PRINT));
    }

    protected function getAll()
    {
        return  new Response("get std versions");
    }

    protected function get()
    {
        $response = array();
        $mapper = new StandardVersionDBMapper();
        $result = $mapper->getById($this->id);
        if ($result instanceof DBError) {
            return new ErrorResponse($result);
        }

        $standard_version = $result->toArray();
        $response['id'] = $standard_version['id'];
        $response['standardId'] = $standard_version['standardId'];
        $response['targetGroups'] = $this->getTargetGroups($standard_version['id']);
        $response['links'] = $this->getLinks($standard_version['documentVersionId']);
        $response['fields'] = $this->getFields();

        return new Response(json_encode($response, JSON_PRETTY_PRINT));
    }

    /**
     * Returns list of target groups connected to the document based on id
     * @param $document_version_id
     * @return array
     */
    private function getTargetGroups($document_version_id)
    {
        $document_version_target_group_mapper = new DocumentVersionTargetGroupDBMapper();
        $target_group_mapper = new TargetGroupDBMapper();

        // Get id of all target groups
        $target_group_ids = $document_version_target_group_mapper->getAllTargetGroupIdsByDocumentVersionId($document_version_id);

        // Get the actual target groups
        $target_groups = array();
        foreach ($target_group_ids as $target_group_id) {
            $tg = $target_group_mapper->getById($target_group_id);
            array_push($target_groups, $tg->toArray());
        }
        return $target_groups;

    }

    /**
     * Returns list of link types, with associated links under each type
     * @param $document_version_id
     * @return array
     */
    private function getLinks($document_version_id)
    {
        $link_mapper = new LinkDBMapper();
        $link_type_mapper = new LinkTypeDBMapper();
        $links = array();

        $link_type_ids = $link_mapper->getLinkTypeIdByDocumentVersionId($document_version_id);
        foreach ($link_type_ids as $id) {
            array_push($links,
                array('linkCategory' => $link_type_mapper->getById($id)->toArray(),
                    'links' => $this->getLinkByType($id, $document_version_id, $link_mapper)));

        }
        return $links;
    }

    /**
     * Returns a list of links under a a link type, based on document version id
     * @param $link_type_id
     * @param $document_version_id
     * @param $link_mapper
     * @return array
     */
    private function getLinkByType($link_type_id, $document_version_id, $link_mapper)
    {
        $links = $link_mapper->getLinksByDocumentVersionIdAndLinkTypeId($link_type_id, $document_version_id);
        $result = array();
        foreach ($links as $link) {
            array_push($result, $link->toArray());
        }
        return $result;
    }

    private function getFields()
    {
        // TODO: return list of all links
        return array();
    }


    protected function update()
    {
        return  new Response("update std version");
        // TODO add update()
    }

    protected function delete()
    {
        return  new Response("delete std version");
        // TODO add delete()
    }
}