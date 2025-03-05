document.addEventListener("DOMContentLoaded", () => {
  // Get all elements with the 'img-thumbnail' class
  const thumbnails = document.getElementsByClassName("img-thumbnail");

  // Add a click event listener to each thumbnail
  Array.from(thumbnails).forEach(thumbnail => {
    thumbnail.addEventListener("click", event => {
      // Remove any existing popup image
      const existingPopup = document.querySelector(".img-popup");
      if (existingPopup) {
        existingPopup.remove();
      }

      // Create the popup container
      const popupContainer = document.createElement("span");
      popupContainer.setAttribute("class", "img-popup");

      // Create the popup image
      const popupImage = document.createElement("img");
      const popupSrc = thumbnail.getAttribute("thumbnail-src");
      popupImage.src = popupSrc;
      popupImage.alt = thumbnail.alt; // Use the clicked image's alt text
      popupImage.style.maxWidth = "80%";
      popupImage.style.maxHeight = "80%";

      // Add the image to the popup container
      popupContainer.appendChild(popupImage);

      // Add a close button to the popup
      const closeButton = document.createElement("span");
      closeButton.innerHTML = "✖";
      closeButton.style.position = "absolute";
      closeButton.style.top = "10px";
      closeButton.style.right = "10px";
      closeButton.style.cursor = "pointer";
      closeButton.style.color = "#fff";
      closeButton.style.fontSize = "20px";
      popupContainer.appendChild(closeButton);

      // Add styles for the popup
      popupContainer.style.position = "fixed";
      popupContainer.style.top = "50%";
      popupContainer.style.left = "50%";
      popupContainer.style.transform = "translate(-50%, -50%)";
      popupContainer.style.background = "rgba(230, 221, 221, 0.8)";
      popupContainer.style.padding = "10px";
      popupContainer.style.borderRadius = "10px";
      popupContainer.style.zIndex = "1000";

      // Add the popup to the DOM
      document.body.appendChild(popupContainer);

      // Add a close functionality
      closeButton.addEventListener("click", () => {
        popupContainer.remove();
      });

      document.addEventListener("click", closePopup);
    });
  });
});

function activateMenu()
{
    const navLinks = document.querySelectorAll('nav a');
    navLinks.forEach(link =>
    {
        if (link.href === location.href)
        {
            link.classList.add('active');
        }
    })
}

document.addEventListener('DOMContentLoaded', activateMenu);

