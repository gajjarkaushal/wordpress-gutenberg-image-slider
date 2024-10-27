
/**
 * All of the code for your admin-facing JavaScript source using venila JS
 * should reside in this file.
 */
WISJS = {
	init: function () {
		document.addEventListener('DOMContentLoaded', function() {
			const addImageButton = document.getElementById('add_image');
			const sliderImagesContainer = document.getElementById('slider_images');
			const sliderImages = sliderImagesContainer.querySelectorAll('.slider-image');
	
			const maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes
			const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif']; // Allowed image file extensions
	
			// intialize drag and drop functionality
			// Loop through each slider image
			if(sliderImages){
				sliderImages.forEach(function(sliderImage) {
					addDragAndDropEvents(sliderImage);
				});
			}
			// Function to create and append an image block
			function addImageBlock(url, title = '', description = '', ctaText = '', ctaUrl = '') {
				const timestamp = Date.now();
		
				// Create the image container div with draggable attribute
				const imageDiv = document.createElement('div');
				imageDiv.classList.add('slider-image');
				imageDiv.setAttribute('draggable', 'true');
				imageDiv.dataset.timestamp = timestamp;
		
				// Hidden input for the image URL
				const urlInput = document.createElement('input');
				urlInput.type = 'hidden';
				urlInput.name = `custom_image_slider_options[slides][${timestamp}][url]`;
				urlInput.value = url;
		
				// Displayed image preview
				const img = document.createElement('img');
				img.src = url;
				img.width = 100;
		
				// Title input
				const titleInput = document.createElement('input');
				titleInput.type = 'text';
				titleInput.name = `custom_image_slider_options[slides][${timestamp}][title]`;
				titleInput.placeholder = 'Title';
				titleInput.value = title;
		
				// Description input
				const descriptionInput = document.createElement('textarea');
				descriptionInput.name = `custom_image_slider_options[slides][${timestamp}][description]`;
				descriptionInput.placeholder = 'Description';
				descriptionInput.value = description;
				descriptionInput.rows = 4; // Set number of visible rows
				descriptionInput.cols = 50; // Set width (optional)
		
				// CTA Button Name input
				const ctaTextInput = document.createElement('input');
				ctaTextInput.type = 'text';
				ctaTextInput.name = `custom_image_slider_options[slides][${timestamp}][cta_text]`;
				ctaTextInput.placeholder = 'CTA Button Name';
				ctaTextInput.value = ctaText;
		
				// CTA URL input
				const ctaUrlInput = document.createElement('input');
				ctaUrlInput.type = 'url';
				ctaUrlInput.name = `custom_image_slider_options[slides][${timestamp}][cta_url]`;
				ctaUrlInput.placeholder = 'CTA Button URL';
				ctaUrlInput.value = ctaUrl;
		
				// Remove button
				const removeButton = document.createElement('button');
				removeButton.type = 'button';
				removeButton.classList.add('remove-image');
				removeButton.textContent = 'Remove';
				removeButton.addEventListener('click', function() {
					sliderImagesContainer.removeChild(imageDiv);
				});
		
				// Append elements to imageDiv
				imageDiv.appendChild(urlInput);
				imageDiv.appendChild(img);
				imageDiv.appendChild(titleInput);
				imageDiv.appendChild(descriptionInput);
				imageDiv.appendChild(ctaTextInput);
				imageDiv.appendChild(ctaUrlInput);
				imageDiv.appendChild(removeButton);
		
				// Append imageDiv to the main container
				sliderImagesContainer.appendChild(imageDiv);
		
				// Add drag event listeners
				addDragAndDropEvents(imageDiv);
			}
			
			// Event listener for Add Image button
			addImageButton.addEventListener('click', function() {
				const mediaUploader = wp.media({
					title: 'Select Images',
					button: {
						text: 'Add Images'
					},
					multiple: true // Allow multiple images
				}).on('select', function() {
					const attachments = mediaUploader.state().get('selection').toArray();
					attachments.forEach(attachment => {
						const file = attachment.toJSON();
						const fileExtension = file.url.split('.').pop().toLowerCase();
		
						// Validate file extension and size
						if (!allowedExtensions.includes(fileExtension)) {
							alert(`Only the following image formats are allowed: ${allowedExtensions.join(', ')}`);
							return;
						}
						if (file.size > maxFileSize) {
							alert(`The file ${file.filename} exceeds the 2 MB size limit.`);
							return;
						}
		
						// If valid, add image block
						addImageBlock(file.url);
					});
				}).open();
			});
		
			// Drag-and-drop functionality
			function addDragAndDropEvents(imageDiv) {
				imageDiv.addEventListener('dragstart', dragStart);
				imageDiv.addEventListener('dragover', dragOver);
				imageDiv.addEventListener('drop', drop);
				imageDiv.addEventListener('dragend', dragEnd);
			}
		
			let draggedElement = null;
		
			function dragStart(event) {
				draggedElement = this;
				event.dataTransfer.effectAllowed = 'move';
				this.classList.add('dragging');
				console.log(draggedElement);
			}
		
			function dragOver(event) {
				event.preventDefault();
				event.dataTransfer.dropEffect = 'move';
		
				const bounding = this.getBoundingClientRect();
				const offset = event.clientY - bounding.top;
		
				if (offset > bounding.height / 2) {
					this.style['border-bottom'] = 'solid 4px #0073aa';
					this.style['border-top'] = '';
				} else {
					this.style['border-top'] = 'solid 4px #0073aa';
					this.style['border-bottom'] = '';
				}
			}
		
			function drop(event) {
				event.preventDefault();
		
				if (this !== draggedElement) {
					const bounding = this.getBoundingClientRect();
					const offset = event.clientY - bounding.top;
		
					if (offset > bounding.height / 2) {
						this.style['border-bottom'] = '';
						this.parentNode.insertBefore(draggedElement, this.nextSibling);
					} else {
						this.style['border-top'] = '';
						this.parentNode.insertBefore(draggedElement, this);
					}
				}
			}
		
			function dragEnd() {
				this.classList.remove('dragging');
				sliderImagesContainer.querySelectorAll('.slider-image').forEach(el => {
					el.style['border-top'] = '';
					el.style['border-bottom'] = '';
				});
			}
		});
	}
};
WISJS.init();