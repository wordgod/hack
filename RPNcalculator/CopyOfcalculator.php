<html>
<head>
	<title>Reverse Polish Notation Calculator</title>
</head>
<body>
	<h1>Calculator</h1>
	<h3>by wusuopubupt</h3>
	<form id="my_form" method="POST" action="<?php echo $_SERVER['SCRIPT_NAME'];?>">
		<input type="text" name="expr" placeholder="input expression to calculate!"/>
	</form>
<?php
if(isset($_POST['expr'])) {
	//$expr = "2/4*8*6";
	$expr = str_split($_POST['expr']);
	$result = cal($expr);
	echo "result is: $result";
}
	
function cal($expr) {
	$postfix_expr = infix2postfix($expr);
	var_dump($postfix_expr);
	$len = count($postfix_expr);
	$stack = array();
	$result = 0;
	for($i = 0; $i < $len; $i++) {
		$var = $postfix_expr[$i];
		if(is_numeric($var)) {
			array_push($stack, $var);
		}
		else {
			$rhs = array_pop($stack); //right hand side
			$lhs = array_pop($stack); //left  hand side
			array_push($stack,operate($lhs,$var,$rhs));
 		}
	}
	return array_pop($stack);
}

function infix2postfix($expr) {
	$len = count($expr);
	$opts = array('+','-','*','/');
	$stack = array();
	$postfix = array();
	for($i = 0; $i < $len; $i++) {
		if(is_numeric($expr[$i])) {
			$postfix[] = $expr[$i];
		}
		else {
			if($expr[$i] == '(') {
				array_push($stack, $expr[$i]);
			}
			if(in_array($expr[$i], $opts)) {
				if(!in_array('(', $stack) && !empty($stack)) {
					array_push($postfix, array_pop($stack));
				}
				array_push($stack, $expr[$i]);
			}
			if($expr[$i] == ')') {
				$op = array_pop($stack); // ) is coming
				array_push($postfix,$op);  // output op
				array_pop($stack); // pop (
			}
		}
	}
	$last_op = array_pop($stack);
	array_push($postfix, $last_op);
	
	return $postfix;
}

function operate($left,$op,$right) {
	switch($op) {
		case '+':
			return $left + $right;
		case '-':
			return $left - $right;
		case '*':
			return $left * $right;
		case '/':
			return $left / $right;
	}
}
?>
</body>
</html>