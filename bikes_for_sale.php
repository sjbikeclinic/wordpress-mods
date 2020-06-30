<?php /* Template Name: BikesForSale */ ?>

<style>
#storeInventory {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

.storeBike {
    display: inline-block;
    margin: 10px 10px;
    cursor: pointer;
}

.storeBike img {
    max-width: 400px !important;
}

.storeBikeTitle {
    text-align: center;
    padding-top: 10px;
}

.bikePrice {
	position: absolute;
	right: 15px;
	top: 13px;
}

.bikePrice > span {
	font-size: 1.2em;
	background-color: green;
	padding: 8px 12px;
}

.bikeImage img {
    width: 100%;
}

.bikeDescription {
    margin-top: 15px;
}
</style>

<?php 

wp_enqueue_style('main-styles', get_template_directory_uri() . '/sjbc.css');
get_header();

while ( have_posts() ) : the_post();
    the_title('<header class="entry-header"><h1 class="entry-title">', '</h1></header>');
    ?><div class="entry-content"><?php
    the_content();
    ?></div><div id="storeInventory"><?php
    
    // include all of the bikes for sale
    $path = get_template_directory() . "/bike_data/";
    $allBikesDir = new DirectoryIterator($path);
    foreach ($allBikesDir as $bikeDirInfo) {
        if (!$bikeDirInfo->isDot()) {
            $bikeDirName = $bikeDirInfo->getFilename();
            $imageURI = NULL;
            $infoStr = file_get_contents($path . "/" . $bikeDirName . "/info.json");
            $info = json_decode($infoStr);
            $bikeDir = new DirectoryIterator($path . "/" . $bikeDirName);
            foreach ($bikeDir as $bikeFile) {
                if ($imageURI == NULL && substr($bikeFile->getFilename(), -4) == ".jpg") {
                    $imageURI = get_template_directory_uri() . "/bike_data/" . $bikeDirName . "/" . $bikeFile->getFilename();
                }
            }
            ?><div class="well storeBike" onclick="showDetails(this)" data-description="<?php echo htmlentities($info->description) ?>">
                <img src="<?php echo $imageURI ?>">
                <div class="storeBikeTitle"><?php echo $bikeDirName . " – " . $info->price ?></div>
            </div><?php
        }
    }

    ?></div><?php
endwhile;

?>

<div id="dlgBikeDetails" class="modal" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Bike Title</h4>
                <div class="bikePrice"><span class="badge">Price</span></div>
            </div>
            <div class="modal-body">
                <div class="bikeImage"><img src=""></div>
                <div class="bikeDescription"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="hideDetails()">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function hideDetails(e) {
    dlg = document.getElementById("dlgBikeDetails");
    dlg.style.display = "none";
}

function showDetails(e) {
    dlg = document.getElementById("dlgBikeDetails");
    titleParts = e.querySelector(".storeBikeTitle").innerText.split("–");
    dlg.querySelector(".modal-title").innerText = titleParts[0].trim();
    dlg.querySelector(".badge").innerText = titleParts[1].trim();

    ta = document.createElement("textarea");
    ta.innerHTML = e.attributes["data-description"].nodeValue;
    dlg.querySelector(".bikeDescription").innerHTML = getFullDescription(ta.value);

    imgURL = e.getElementsByTagName("img")[0].attributes["src"].nodeValue;
    imgSrc = dlg.querySelector(".bikeImage").getElementsByTagName("img")[0].attributes["src"];
    imgSrc.nodeValue = e.getElementsByTagName("img")[0].attributes["src"].nodeValue;

    dlg.style.display = "block";
}

function getFullDescription(bikeDescription, bikeIndexUrl) {
    let postAmbleStart = "Get a free one-month membership ($20 value) with the purchase of any bike from the San Jose Bike Clinic. Take advantage of this offer to learn from the experts and keep your bike running in tip top shape!<br/><br/>This bike just had a tune up and complete safety inspection by members of San Jose Bike Clinic, a fiscally sponsored program of Silicon Valley Bicycle Coalition*. All proceeds go to support our do-it-yourself community bicycle workshop at our space in downtown San Jose! You can find us at 80 N. 4th St., Suite 10.Come in during our shop hours or call us to make an appointment. Cash or credit card only.<br/><br/>";

    let bikeIndexInfo = "San Jose Bike Clinic has verified this bike and registered it on BikeIndex.org:<br/><br/>";

    let postAmbleEnd = "Thank you! And ride on...<br/><br/>*Silicon Valley Bicycle Coalition is a 501(c)-3 non-profit. Find out more at www.bikesiliconvalley.org.";

    let result = bikeDescription + "<br/><br/><span style=\"font-size:14px\">" + postAmbleStart;
    if (bikeIndexUrl) {
        result += bikeIndexInfo + "<br/><br/>" + bikeIndexUrl + "<br/><br/>";
    }
    result += postAmbleEnd +"</span>";
    return result;
}
</script>

<?php

get_footer();

?>
