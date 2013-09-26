<?php 

class DreamProfilerHelper {

	public function __construct(){
		
		foreach(get_included_files()as$file){if(stristr($file,'DreamProfiler.php')===false){include_once('DreamProfiler\DreamProfiler.php');}}
	}
	
	public function echoTable(){
		$profilerContents = DreamProfiler::get_profile();
		if($profilerContents){
			echo '
			<style>
			.red {
				color:rgb(255,0,0);
			}
			</style>
			';
			echo "<table>";
			echo "
			<tr>
				<td><b>Class::Function</b></td>
				<td><b>CombinedRunTime</b></td>
				<td><b>TrueMemUsageVSprev</b></td>
				<td><b>emallocVSprev</b></td>
				<td><b>LastRunMemUseReal</b></td>
				<td><b>LastRunMemuseEmalloc</b></td>
				<td><b>SupposedCallCountButNotReallySureWhatTheFuckThisIs</b></td>
			</tr>";
			foreach($profilerContents as $name=>$contents){
				echo '<tr><td colspan="7"><b>'.$name.'</b></td></tr>';
				foreach($contents as $detailedName=>$content){
					echo '<tr><td>'.$detailedName.'</td>';
					echo '<td '.($content['time']>0.1?'class="red"':'').'>'.number_format($content['time'],7).'</td>';
					echo '<td>'.$content['memory_real'].'</td>';
					echo '<td>'.$content['memory_emalloc'].'</td>';
					echo '<td>'.$content['memory_current_real'].'</td>';
					echo '<td>'.$content['memory_current_emalloc'].'</td>';
					echo '<td>'.$content['call_count'].'</td></tr>';
				}
			}
			echo "</table>";
		}
	}
}
?>