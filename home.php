<?php
defined('_VALID_ACCESS') or die('Restricted Access');
//***********************************************************************************************مشخصات اولیه ادمین
$title[$section][$module] = 'داشبورد';
$admi = @$_SESSION['admin'];
$sql = "SELECT * FROM xxadmin
		NATURAL LEFT JOIN xxpermissions
		WHERE xusername='$admi'";
$homeadmin = $db->getRow($sql);		
$permi=$homeadmin['xpermissionid'];
$aid=$homeadmin['xadminid'];
$sql = "SELECT * FROM xxpermissions 
		WHERE xpermissionid='$permi'";		
$men = $db->getRow($sql);
$Menu=$men['xpermenu'];
//*********************************************************************************************داشبورد اطلاعات کاربر
//******************************************************************شعبه
//$sql = "SELECT * FROM xxshoab 
//		WHERE xshobeid='".$homeadmin['xshobeid']."'";		
//$ashobe = $db->getRow($sql);
//$ashobename = $ashobe['xshobename']." (".$ashobe['xshobename'].")";
$ashobename = "دفتر مرکزی - گیشا";
$smarty->assign('ashobename', $ashobename);
//******************************************************************بررسی قدرت پسورد
$strength=0;
$Pass=$homeadmin['xpassword'] ;
if(strlen($Pass)>6)
	$strength++;
if (preg_match('/[A-Z]/', $Pass))
{
	if(preg_match('/[a-z]/', $Pass))
	$strength++;
}
if (preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]|[A-Za-z].*[0-9].*[A-Za-z]/', $Pass))
	$strength++;
if (preg_match('/[!%&@#$^*?_~]+/', $Pass))
	$strength++;
if(strlen($Pass)>12)	
	$strength++;
if(strlen($Pass) <7)
	$PassLenght="کوتاه";
elseif($strength<2)
	$PassLenght="ضعیف";
elseif($strength==2)
	$PassLenght="خوب";
elseif($strength==3 || $strength==4)
	$PassLenght="قوی";
else
	$PassLenght="خیلی قوی";
$smarty->assign('PassLenght', $PassLenght);
//************************************************************************عمق دسترسی
$acdeepid=$homeadmin['xacdeepid'];
$sql ="SELECT xacdeep FROM xxacdeep
	   WHERE xacdeepid=$acdeepid";
$acdeep = $db->getOne($sql);
$permis=$homeadmin['xpermissionname'];
$smarty->assign('acdeep', $acdeep);
$smarty->assign('permis', $permis);
//********************************************************************زمان آخرین ورود
$sql ="SELECT xtime FROM xxlog
	   WHERE xusername='$admi'
	   ORDER BY xtime DESC
	   LIMIT 1,1";
$lastloginu = $db->getOne($sql);
if (!$lastloginu)
{
	$lastloginus = "هیچگاه وارد نشده‌اید!";
}
else
{
	$lastloginus = jdate("D d M Y  ساعت H:i", 0, 0, $lastloginu+$timedef*60*60);
	$lastlogindate = date("d-m-Y", $lastloginu+$timedef*60*60);
}
$smarty->assign('lastloginus', $lastloginus);
//************************************************************************************************************************کارتابل
//*******************************************************************لینک خبر
$newslinksreckol=0;$newslinksrec=0;
if(strpos($Menu, "newslinks-list")>0)
{
	$sql = "SELECT COUNT(*) FROM xxnewslinks 
			WHERE xnewslinksnewsid='' AND xnewslinksdeleted='no'";		
	$newslinksrec = $db->getOne($sql);
	$sql = "SELECT COUNT(*) FROM xxnewslinks 
			WHERE xnewslinksdeleted='no'";		
	$newslinksreckol = $db->getOne($sql);
}
$smarty->assign('newslinksrec', @$newslinksrec);
$smarty->assign('newslinksreckol', @$newslinksreckol);
//********************************************************************کامنت‌های مطالب سایت
if(strpos($Menu, "comment-list")>0)
{
	$sql = "SELECT COUNT(*) FROM xxcomment 
		WHERE xcommentfinalstate='inprogress' AND xcommentdeleted='no'";		
	$commentrec = $db->getOne($sql);
	$sql = "SELECT COUNT(*) FROM xxcomment 
		WHERE xcommentdeleted='no'";		
	$commentreckol = $db->getOne($sql);
}
$smarty->assign('commentrec', @$commentrec);
$smarty->assign('commentreckol', @$commentreckol);
//***************************************************************تماس با ما
$sql = "SELECT COUNT(*) FROM xxdirectrel 
	WHERE xdirectrelstatus='unread' AND xdirectreldeleted='no'";		
$directrelrec = $db->getOne($sql);
$sql = "SELECT COUNT(*) FROM xxdirectrel 
	WHERE xdirectreldeleted='no'";		
$directrelreckol = $db->getOne($sql);
$smarty->assign('directrelrec', @$directrelrec);
$smarty->assign('directrelreckol', @$directrelreckol);
//***********************************************************************گزارش اشکال در سایت
if(strpos($Menu, "siteproblems-list")>0)
{
	$sql = "SELECT COUNT(*) FROM xxsiteproblems 
		WHERE xsiteproblemsstatus='unread' AND xsiteproblemsdeleted='no'";		
	$siteproblemsrec = $db->getOne($sql);
	$sql = "SELECT COUNT(*) FROM xxsiteproblems 
		WHERE xsiteproblemsdeleted='no'";		
	$siteproblemsreckol = $db->getOne($sql);
}
$smarty->assign('siteproblemsrec', @$siteproblemsrec);
$smarty->assign('siteproblemsreckol', @$siteproblemsreckol);
//**********************************************************************************ارجاعیات
if(strpos($Menu, "erjamy-list")>0)
{
	$sql = "SELECT COUNT(*) FROM xxerja 
		WHERE xerjastatus='notprogressed' AND xerjadeleted='no' AND xerjareceiverid='$aid'";		
	$erjarec = $db->getOne($sql);
	$sql = "SELECT COUNT(*) FROM xxerja 
		WHERE xerjadeleted='no' AND xerjareceiverid='$aid'";		
	$erjareckol = $db->getOne($sql);
}
$smarty->assign('erjarec', @$erjarec);
$smarty->assign('erjareckol', @$erjareckol);
//***************************************************************************نوتیفیکیشن فرم 
if(strpos($Menu, "namayandegi-list")>0)
{
	$sql = "SELECT COUNT(*) FROM xxnamayandegi
			WHERE xnamayandegistatus='unread' AND xdeleted='no'";		
	$namayandegirec = $db->getOne($sql);
	$sql = "SELECT * FROM xxnamayandegi
			WHERE xnamayandegistatus='unread' AND xdeleted='no'";		
	$allnewnamayandegiform = $db->getAll($sql);
	$namayandeginewrec=0;
	foreach($allnewnamayandegiform as $key=>$val)
	{
		if (strtotime($val['xnamayandegiregdate'])>=$lastloginu)
			{	
				$namayandeginewrec++;
			}
	}
}
$smarty->assign('namayandegirec', @$namayandegirec);
$smarty->assign('namayandeginewrec', @$namayandeginewrec);
//*****************************************************************************متقاضی همکاری
if(strpos($Menu, "hamkar-list")>0)
{
	$sql = "SELECT COUNT(*) FROM xxhamkar
			WHERE xhamkartype='Unknown' AND xhamkardeleted='no'";		
	$hamkarrec = $db->getOne($sql);
	$sql = "SELECT COUNT(*) FROM xxhamkar
			WHERE xhamkartype='Unknown' AND xhamkardeleted='no' AND xhamkarregtime>='$lastloginu'";		
	$hamkarnewrec = $db->getOne($sql);
}
$smarty->assign('hamkarrec', @$hamkarrec);
$smarty->assign('hamkarnewrec', @$hamkarnewrec);
//*****************************************************************************متقاضیان تدریس
if(strpos($Menu, "teacher-list")>0)
{
	$sql = "SELECT COUNT(*) FROM xxpartner
			WHERE xtype='Unknown' AND xdeleted='no'";		
	$tadrisrec = $db->getOne($sql);
	$sql = "SELECT * FROM xxpartner
			WHERE xtype='Unknown' AND xdeleted='no'";		
	$allteacherform = $db->getAll($sql);
	$tadrisnewrec=0;
	foreach($allteacherform as $key=>$val)
	{
		if (strtotime($val['xregdate'])>=$lastloginu)
			{	
				$tadrisnewrec++;
			}
	}
}
$smarty->assign('tadrisrec', @$tadrisrec);
$smarty->assign('tadrisnewrec', @$tadrisnewrec);
//**********************************************************************************درخواست مشارکت
if(strpos($Menu, "mosharekat-list")>0)
{
	$sql = "SELECT COUNT(*) FROM xxmosharekat
			WHERE xmosharekatstatus='Unknown' AND xmosharekatdeleted='no'";		
	$mosharekatrec = $db->getOne($sql);
	$sql = "SELECT COUNT(*) FROM xxmosharekat
			WHERE xmosharekatstatus='Unknown' AND xmosharekatdeleted='no' AND xmosharekatregtime>='$lastloginu'";		
	$mosharekatnewrec = $db->getOne($sql);
}
$smarty->assign('mosharekatrec', @$mosharekatrec);
$smarty->assign('mosharekatnewrec', @$mosharekatnewrec);
//************************************************************************************************************************ اطلاعات بازدید
$allvisit 	= $db->getOne("SELECT COUNT(*) FROM xxvisit");
$allvisitinit = $db->getOne("SELECT xallvisitinit FROM xx_setting");
$allvisit	=$allvisit+$allvisitinit;
$dayvisit 	= $db->getOne("SELECT COUNT(*) FROM xxvisit WHERE xlastactive > '".(time()-86400)."'");
$yesdayvisit 	= $db->getOne("SELECT COUNT(*) FROM xxvisit WHERE xlastactive < '".(time()-86400)."' AND  xlastactive >'".(time()-172800)."'");
//$dayvisit1 	= $db->getOne("SELECT COUNT(*) FROM xxvisit WHERE xdepid='1' AND xlastactive > '".(time()-86400)."'");
//$dayvisit4	= $db->getOne("SELECT COUNT(*) FROM xxvisit WHERE xdepid='4' AND xlastactive > '".(time()-86400)."'");
//$dayvisit6 	= $db->getOne("SELECT COUNT(*) FROM xxvisit WHERE xdepid='6' AND xlastactive > '".(time()-86400)."'");
//$dayvisit12 	= $db->getOne("SELECT COUNT(*) FROM xxvisit WHERE xdepid='12' AND xlastactive > '".(time()-86400)."'");
//$dayvisit14 	= $db->getOne("SELECT COUNT(*) FROM xxvisit WHERE xdepid='14' AND xlastactive > '".(time()-86400)."'");
$online 	= $db->getOne("SELECT COUNT(*) FROM xxvisit WHERE xlastactive > '".(time()-1800)."'");
$smarty->assign('allvisit', $allvisit);
$smarty->assign('dayvisit', $dayvisit);
$smarty->assign('yesdayvisit', $yesdayvisit);
//$smarty->assign('dayvisit1', $dayvisit1);
//$smarty->assign('dayvisit4', $dayvisit4);
//$smarty->assign('dayvisit6', $dayvisit6);
//$smarty->assign('dayvisit12', $dayvisit12);
//$smarty->assign('dayvisit14', $dayvisit14);
$smarty->assign('online', $online);

$tplModule = '_home.tpl';
?>