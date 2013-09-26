<?php
class Reusable_Model_Object
{
	static function getPublicObjectVars($obj) {
	  return get_object_vars($obj);
	}
}