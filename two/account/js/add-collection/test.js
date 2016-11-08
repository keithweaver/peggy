$(document).ready(function(){
	loadTestData();
});
function loadTestData(){
	chapters.push({ id : 1, name : "Are You Pregnant?", range : "15-17" });
	chapters.push({ id : 1, name : "Your Pregnancy Profile", range : "18-74" });
	chapters.push({ id : 1, name : "Your Pregnancy Lifestyle", range : "75-96" });
	chapters.push({ id : 1, name : "Nine Months of Eating Well", range : "97-119" });
	
	displayChapters();

}