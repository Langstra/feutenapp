<?php

function q($query, $params = array())
{
	global $sql;
	$prep = $sql->prepare($query);
	$result = $prep->execute($params);
	if ($result)
	{
		return $prep;
	}
	else
	{
		return false;
	}
}

function n($resource)
{
	$count = $resource->rowCount();
	return $count;
}

function f($fetch)
{
	$results = $fetch->fetchAll(PDO::FETCH_ASSOC);
	return $results;
}

function last()
{
	global $sql;
	return $sql->lastInsertId();
}

?>