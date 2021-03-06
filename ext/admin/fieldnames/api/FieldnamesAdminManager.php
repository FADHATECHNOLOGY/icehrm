<?php
if (!class_exists('FieldnamesAdminManager')) {
    class FieldnamesAdminManager extends AbstractModuleManager{
        public function initializeUserClasses(){

        }

        public function initializeFieldMappings(){

        }

        public function initializeDatabaseErrorMappings(){

        }

        public function setupModuleClassDefinitions(){
            $this->addModelClass('FieldNameMapping');
            $this->addModelClass('CustomField');
        }
    }
}

if (!class_exists('FieldNameMapping')) {
    class FieldNameMapping extends ICEHRM_Record {
        var $_table = 'FieldNameMappings';

        public function getAdminAccess(){
            return array("get","element","save","delete");
        }

        public function getUserAccess(){
            return array();
        }

        public function getAnonymousAccess(){
            return array("get","element");
        }
    }
}

if (!class_exists('CustomField')) {
	class CustomField extends ICEHRM_Record {
		var $_table = 'CustomFields';

		public function getAdminAccess(){
			return array("get","element","save","delete");
		}

		public function getUserAccess(){
			return array();
		}

		public function getAnonymousAccess(){
			return array("get","element");
		}

        public function validateSave($obj){
            $type = $obj->type;
            $baseObject = new $type();
            $fields = $baseObject->getObjectKeys();
            if(isset($fields[$obj->name])){
                return new IceResponse(IceResponse::ERROR,"Column name already exists by default");
            }

            $cf = new CustomField();
            if(empty($obj->id)){
                $cf->Load("type = ? and name = ?",array($obj->type, $obj->name));
                if($cf->name == $obj->name){
                    return new IceResponse(IceResponse::ERROR,"Another custom field with same name has already been added");
                }
            }else{
                $cf->Load("type = ? and name = ? and id <> ?",array($obj->type, $obj->name, $obj->id));
                if($cf->name == $obj->name){
                    return new IceResponse(IceResponse::ERROR,"Another custom field with same name has already been added");
                }
            }

            return new IceResponse(IceResponse::SUCCESS,"");
        }

	}
}