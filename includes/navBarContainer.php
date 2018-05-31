<div id="navBarContainer">
        <nav class="nav-bar">
          <span role="link" tabindex="0" onclick='openPage("index.php")' class="logo" >
            <img src="./assets/images/icons/timify-icon3.png" alt="Icon">
          </span>
          <div class="group">
            <div class="nav-item" onclick='openPage("search.php")'>
            <span role="link" tabindex="0"  class="nav-item-link">Search
                <img src="assets/images/icons/search.png" alt="Search" class="icon">
              </span>
            </div>
          </div>
          <div class="group"> 
            <div class="nav-item nav-hover" onclick='openPage("browse.php")'>
            <span role="link" tabindex="0"  class="nav-item-link">Browse Our Library</span>
            </div><div class="nav-item nav-hover" onclick='openPage("yourMusic.php")'>
            <span role="link" tabindex="0"  class="nav-item-link">Your Music</span>
            </div><div class="nav-item nav-hover" onclick='openPage("profile.php")'>
            <span role="link" tabindex="0"  class="nav-item-link"><?php echo $userObj->getFirstAndLastName(); ?></span>
            </div>
          </div>
        </nav>
      </div>