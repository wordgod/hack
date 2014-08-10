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
	$expr = readin($_POST['expr']);
	$result = cal($expr);
	echo "result is: $result";
}

function readin($expr) {
	$expr = str_split($expr);
	$len  = count($expr);
	$infix = array();
	$opts  = array('+','-','*','/','(',')');
	$num = "";
	for($i = 0; $i < $len; $i++) {
		if(in_array($expr[$i], $opts) || ($i == $len-1)) {
			if($num = floatval($num)) {
				array_push($infix, $num);
				$num = "";
			}
			array_push($infix, $expr[$i]);
		}
		else {
			$num .= $expr[$i];
		}
	}
	var_dump($infix);
	return $infix;
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
			if($expr[$i] == '+' || $expr[$i] == '-' || $expr[$i] == '*' || $expr[$i] == '/') {
				if(!empty($stack)){
					$op = array_pop($stack);
					if($op != '(' && (level($op) >= level($expr[$i]))) {
						array_push($postfix,$op);
					}
					else {
						array_push($stack, $op);
					}
				}
				array_push($stack, $expr[$i]);
			}
			if($expr[$i] == ')') {
				while(($op = array_pop($stack)) != '(') {
					array_push($postfix,$op);
				}
			}
		}
	}
	// 8-(2-6)*4
	while(($last_op = array_pop($stack)) != NULL){
		array_push($postfix, $last_op);
	}
	
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
			return $left / $right;  // should check right is 0 or not!
	}
}

function level($op) {
	switch ($op) {
		case '+':
		case '-':
			return 1;
		case '*':
		case '/':
			return 2;
	}
}


?>
</body>
</html>
