<?php 
    // $message = $_GET['codename'];
    // $codename =str_replace(' ','',$message);
	$codename = $_GET['codename'];
    // $codename = 'Q5MC';
	$db = new PDO("sqlsrv:Server=172.19.32.50\CIMSQL1;Database=Project_tracking_online", "sa", 
	":b,@,bo1");
    // echo $codename;

	$stmt = $db->query("SELECT * FROM dbo.PJ_Tracking_online
WHERE dbo.PJ_Tracking_online.PJ_NickName = '{$codename}'");

	// while ($row = $stmt->fetch()) {
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>Project 4C</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<style type="text/css">
		
		#p3{
			float: right;
		}

	/*	div{
			 width:100px;
		}*/
		input[type='checkbox'] {

			right: 150px;
		}
        img{
            float: right;
        }
        
	</style>
</head>
<body>
<form>
<?php if($codename == 'MountSterling') {?>
<table class="table" style="width:100%;border:4px solid black;background-color:;border-collapse: collapse;">
    <tr>
       <td class="auto-style5" style="border:4px solid black;">
    <table>
    <td>
        <table>
         <tr>
            <td><label class="control-label">P1</label></td>
            <td><label class="control-label">P2</label></td>
            <td><label class="control-label">P3</label></td>
            <td><label class="control-label">P4</label></td>
            <td></td>
        </tr>
        <tr>
            <td><input type="checkbox" value=""></td>
            <td></td>
            <td></td>
            <td></td>
            <td>&nbsp;BU PM: Tina Small</td>
        </tr>
        <tr>
            <td><input type="checkbox" value=""></td>
            <td><input type="checkbox" value=""></td>
            <td><input type="checkbox" value=""></td>
            <td><input type="checkbox" value=""> </td>
        </tr>
        <tr>
            <td></td>    
            <td></td>
            <td></td>
            <td><input type="checkbox" value=""> </td>
            <td>&nbsp;SCP PM: YS Park</td>
        </tr>
        <tr>
            <td><input type="checkbox" value="" checked=""></td>    
            <td><input type="checkbox" value=""></td>
            <td><input type="checkbox" value=""></td>
            <td><input type="checkbox" value=""> </td>
            <td>&nbsp;EM team: Kasinee Normai<br>
    Subcon PM: Alongkorn M.
</td>
        </tr>
       </table>
    </td>
    <td align="right">&nbsp;&nbsp;<img src="Picture1.png" alt="Smiley face"></td>
    </table>
    

       <table style="width:600px;" class="col-md-4">
      
            <tr>
                <td class="auto-style2">Cust Project ID# :<?php echo $row['Cust_ID']; ?></td>
                <td style="width:150px;height: 100px;"><asp:Label ID="lbCust" runat="server"></asp:Label></td>
                <td rowspan="15" >
                    <asp:GridView ID="gvImages" runat="server" AutoGenerateColumns="false" OnRowDataBound="gvImages_RowDataBound" Width="200px">
                    <Columns>
                        <asp:TemplateField >
                            <ItemTemplate>
                                <asp:Image ID="Image1" runat="server" />
                            </ItemTemplate>
                        </asp:TemplateField>
                    </Columns>
                </asp:GridView>
                    <asp:Image ID="Image1" runat="server" Height="190px" Width="100%" Visible="false" />
                </td>
            </tr>
            <tr>
                <td class="auto-style2">Package : </td>
                <td>RWW/21/HR</td>
            </tr>
            <tr>
                <td class="auto-style2">ARC# : </td>
                <td><font color="red">N/A </font>;</td>
                <td class="auto-style2">ARM#: </td>
                <td>PKG004486A</td>
            </tr>
            <tr>
                <td class="auto-style2">Package Classification : </td>
                <td>Derivative</td>
            </tr>
            <tr>
                <td class="auto-style2">eQDB# : </td>
                <td><font color="red">Pending</font></td>
            </tr>
            <tr>
                <td class="auto-style2">Enhanced MQ:</td>
                <td><font color="red">Pending</font>, before or after RTM</td>

            </tr>
            <tr>
                <td class="auto-style2">Industry:</td>
                <td>commercial</td>
            </tr>
            <tr>
                <td class="auto-style2">Silicon and scribe width: </td>
                <td>(LBC9PLV 1um AlCu (0.5%), 67um)</td>
            </tr>
        </table>
            <table class="table table-bordered col-md-4" style="width:300px; height: 25px; background-color:;border:2px solid black;" >
                <tr>
                    <th>Material Name</th>
                    <th>Material</th>
                </tr>
                <tr>
                    <td>Lead Frame</td>
                    <td>FR1544</td>
                </tr>
                <tr>
                    <td>Flux</td>
                    <td>KZ0003</td>
                </tr>
                <tr>
                    <td>Component-cap</td>
                    <td>Pending</td>
                </tr>
                <tr>
                    <td>Solder paste</td>
                    <td>PZ0082</td>
                </tr>
                <tr>
                    <td>Compound</td>
                    <td>CZ0334</td>
                </tr>
            </table>
            
    </td>
    <td class="auto-style5" style="border:4px solid black;">
       <table style="border-collapse: collapse;">
           <td colspan="2"><b> >>Project Timeline</b></td>
       </table>
          
             <table class="table table-bordered col-md-4" style="width:200px; height: 50px; background-color:;border:2px solid black;" align="right">
                <tr>
                    <th>Major Milestones</th>
                    <th>POR</th>
                    <th>Fcst/Actual</th>
                </tr>
                <tr>
                    <td>Design Locked</td>
                    <td >07/04/2019</td>
                    <td style="background-color:green;">07/04/2019</td>
                </tr>
                <tr>
                    <td>Risk assessment</td>
                    <td>07/04/2019</td>
                    <td style="background-color:green;">07/04/2019</td>
                </tr>
                <tr>
                    <td>Water Delivert</td>
                    <td>TBD</td>
                    <td>TBD</td>
                </tr>
                <tr>
                    <td>Capacitor delivery</td>
                    <td>TBD</td>
                    <td>TBD</td>
                </tr>
                <tr>
                    <td>LC</td>
                    <td>TBD</td>
                    <td>TBD</td>
                </tr>
                 <tr>
                    <td>1st Cust./Engr. Sample</td>
                    <td>TBD</td>
                    <td>TBD</td>
                </tr>
                 <tr>
                    <td>LAR</td>
                    <td>TBD</td>
                    <td>TBD</td>
                </tr>
                 <tr>
                    <td>Qual & Rel Test completed</td>
                    <td>TBD</td>
                    <td>TBD</td>
                </tr>
                 <tr>
                    <td>RTP</td>
                    <td>06/02/2021</td>
                    <td>06/02/2021</td>
                </tr>
            </table>
        <!-- </table> -->
    </td>
  </tr>
  <tr>
    <td class="auto-style5" style="border:4px solid black;">
      <table style="border-collapse: collapse;">
        <tr>
            <td colspan="2"><b> >>Risk Concern</b></td>
        </tr>
        <tr> 
            <td colspan="2"><b> Medium risk: Quality of capacitor and die after reflow</b></td>
        </tr>
       </table>
       <table class="table table-bordered col-md-4" style="width:400px; height: 50px; background-color:;border:2px solid black;" align="center">
           <tr>
               <td>#</td>
               <td>Key Activity</td>
               <td>Who</td>
               <td>When</td>
           </tr>
           <tr>
               <td>1</td>
               <td>Follow up capacitor SID# and delivery</td>
               <td>Pisit T.</td>
               <td>07/24/2019</td>
           </tr>
           <tr>
               <td>2</td>
               <td>LF Delivery</td>
               <td>Pisit T.</td>
               <td>09/20/2019</td>
           </tr>
           <tr>
               <td>3</td>
               <td>Stencil delivery</td>
               <td>Pisit T.</td>
               <td></td>
           </tr>
       </table>
    </td>
    <td class="auto-style5" style="border:4px solid black;">
        <table style="border-collapse: collapse;">
            <tr>
                <td colspan="2"><b> >>Development Plan</b></td>
            </tr>
            <tr> 
                <td colspan="2"><p> - LC/DOE of Capacitor, FC attach, and mold.</p></td>
            </tr>
            <tr> 
                <td colspan="2"><p> - Full process validation (PV) with LAR.</p></td>
            </tr>
            <tr>
                <td colspan="2"><b> >>Help Needed</b></td>
            </tr>
            <tr> 
                <td colspan="2"><p> - Provide detail for capacitor stock</p></td>
            </tr>
            <tr> 
                <td colspan="2"><p> - Wafer schedule</p></td>
            </tr>
            <tr> 
                <td colspan="2"><p> - Product application with max. voltage required.</p></td>
            </tr>
        </table>
    </td>
  </tr>
</table>
<?php } if($codename == 'Q5MC'){ ?>
    <table class="table" style="width:100%;border:4px solid black;background-color:;border-collapse: collapse;">
    <tr>
       <td class="auto-style5" style="border:4px solid black;">
    <table style="height: 100px;">
        <tr>
            <td colspan="2"><b> >> Team Member</b></td>
        </tr>
    <tr>
       <th>PM :</th><td>Ai-lada Suwanchatree</td>
    </tr>
    <tr>
       <th>SCP :</th><td>Jonathan Noquil</td>
    </tr>
    <tr>
       <th>EM PL :</th><td>Ai-lada Suwanchatree</td>
    </tr>
    <tr>
       <th>Planning :</th><td>Parames Munusamy </td>
    </tr>
     <tr>
       <th>UTL PM :</th><td>Alongkorn Makmeesup </td>
    </tr>
    <tr>
        <br>
        <td align="right"><img src="Picture2.png" alt="Smiley face"></td>
    </tr>
    </table>
       <table style="width:500px;" class="col-md-4">
      
            <tr>
                <!-- <td class="auto-style2">Cust Project ID# :</td> -->
                <td style="width:150px;height: 100px;"><asp:Label ID="lbCust" runat="server"></asp:Label></td>
                <td rowspan="15" >
                    <asp:GridView ID="gvImages" runat="server" AutoGenerateColumns="false" OnRowDataBound="gvImages_RowDataBound" Width="200px">
                    <Columns>
                        <asp:TemplateField >
                            <ItemTemplate>
                                <asp:Image ID="Image1" runat="server" />
                            </ItemTemplate>
                        </asp:TemplateField>
                    </Columns>
                </asp:GridView>
                    <asp:Image ID="Image1" runat="server" Height="190px" Width="100%" Visible="false" />
                </td>
            </tr>
            <tr>
                <td class="auto-style2">Device :</td>
                <td>See 3 matrix code</td>
            </tr>
            <tr>
                <td class="auto-style2">Package : </td>
                <td>12L MDFN 6x5</td>
            </tr>
            <tr>
                <td class="auto-style2">ARC# : </td>
                <td>N/A</td>
            </tr>
            <tr>
                <td class="auto-style2">E-RTP : </td>
                <td>WW26’19</td>
            </tr>
            <tr>
                <td class="auto-style2">SBE : </td>
                <td>APP</td>
            </tr>
            <tr>
                <td class="auto-style2">MSL2</td>
                <td></td>
            </tr>
          
        </table>
            <table class="table table-bordered col-md-4" style="width:200px; height: 50px; background-color:;border:2px solid black;" >
                <tr>
                    <th>Device</th>
                    <th>CSD95378BQ5MC</th>
                    <th>CSD95378BQ5MC</th>
                    <th>CSD95472BQ5MC</th>
                </tr>
                <tr>
                    <td>LF</td>
                    <td>FR5216</td>
                    <td>FR5215</td>
                    <td>FR5214</td>
                </tr>
                <tr>
                    <td>HS Clip</td>
                    <td>NA0080 A0</td>
                    <td>NA0080 A0</td>
                    <td>NA0009 B0</td>
                </tr>
                <tr>
                    <td>LS Clip</td>
                    <td>NA0079 A0</td>
                    <td>NA0081 A0</td>
                    <td>NA0010 B0</td>
                </tr>
                <tr>
                    <td >Mold Compound</td>
                    <td colspan="3"><center>CEL9220HF 10 </center></td>
                    
                </tr>
            </table>
            
    </td>
    <td class="auto-style5" style="border:4px solid black;">
       <table style="border-collapse: collapse;">
           <td colspan="2"><b> >> Project Timeline</b></td>
       </table>
          
             <table class="table table-bordered col-md-4" style="width:400; height: 50px; background-color:;border:2px solid black;" align="right">
                <tr>
                    <th>Major Milestones</th>
                    <th>POR</th>
                    <th>Fcst/Actual</th>
                </tr>
                <tr>
                    <td>LC</td>
                    <td>Apr 23'19</td>
                    <td style="background-color:green;">Apr 23'19/td>
                </tr>
                <tr>
                    <td>Qual Assy Ship</td>
                    <td>Jun 03'19</td>
                    <td style="background-color:green;">Jun 03'19</td>
                </tr>
                <tr>
                    <td>Test Program Ready</td>
                    <td>July 12'19</td>
                    <td style="background-color:yellow;">Aug 01'19</td>
                </tr>
                <tr>
                    <td>Complete Rel test</td>
                    <td>Aug 06'19</td>
                    <td style="background-color:yellow;">Oct 23'19</td>
                </tr>
                <tr>
                    <td>Complete Pre-Production Safe launch</td>
                    <td>Aug 05'19</td>
                    <td style="background-color:yellow;">Jan 03'19</td>
                </tr>
                
                
            </table>
         <!-- Green: delay ≤ 2 wk / Yellow: delay 3-8 wk / Red: delay > 8 wk -->
       <table class="table" style="background-color:;">
            <tr><td>Green: delay ≤ 2 wk / Yellow: delay 3-8 wk / Red: delay > 8 wk</td></tr>
       </table>
    </td>
  </tr>
  <tr>
    <td class="auto-style5" style="border:4px solid black;">
      <table style="border-collapse: collapse;">
        <tr>
            <td colspan="2"><b> >> Accomplishment</b></td>
        </tr>
        <tr> 
            <td colspan="2"><p>- Completed buyoff tool and material</p></td>
        </tr>
        <tr> 
            <td colspan="2"><p>- Completed DOE process</p></td>
        </tr>
        <tr> 
            <td colspan="2"><p>- Assembly Qual units</p></td>
        </tr>
        <tr> 
            <td colspan="2"><p>- Test development</p></td>
        </tr>
        <tr>
            <td colspan="2"><b> >> Ongoing activity</b></td>
        </tr>
        <tr> 
            <td colspan="2"><p>- On going Reliadility test</p></td>
        </tr>
        
       </table>
    
    </td>
    <td class="auto-style5" style="border:4px solid black;">
        <table style="border-collapse: collapse;">
            <tr>
                <td colspan="2"><b> >> Focus</b></td>
            </tr>
            <tr> 
                <td colspan="2"><p> - DOE for each process</p></td>
            </tr>
            <tr> 
                <td colspan="2"><p> - Monitor LC lots and collecting yield pareto and Rel test to improve before Qual lot</p></td>
            </tr>
            <tr> 
                <td colspan="2"><p> - Monitor schedule</p></td>
            </tr>
            <tr>
                <td colspan="2"><b> >> Challenge</b></td>
            </tr>
            <tr> 
                <td colspan="2"><p> - WB concerns related to new pad structure and wire type</p></td>
            </tr>
            <tr> 
                <td colspan="2"><p> - Project urgency causing shortened process development</p></td>
            </tr>
            <tr>
                <td colspan="2"><b> >>Help Needed</b></td>
            </tr>
            <tr> 
                <td colspan="2"><p> - From TI to support test release on time and ship the unit for group 1 and 2 for trimmed evaluation</p></td>
            </tr>
           
        </table>
    </td>
  </tr>
</table>
 <?php }
// } 
?>
</form>
</body>

</html>