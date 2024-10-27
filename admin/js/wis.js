
// Drag and drop functionality variable
let draggedElement = null;
let sliderImagesContainer = null;
let MultipleImageCnt = 0;
/**
 * All of the code for your admin-facing JavaScript source using venila JS
 * should reside in this file.
 */
WISJS = {
	
	maxFileSize : 2 * 1024 * 1024, /* 2 MB in bytes */
	allowedExtensions:['jpg', 'jpeg', 'png', 'gif'], /* Allowed image file extensions */
	/**
	 * Initializes the WordPress Image Slider admin functionality.
	 * Sets up event listeners and drag-and-drop functionality for image blocks.
	 * Handles image uploads, size and format validation, and dynamic creation
	 * of image blocks with title, description, and CTA inputs.
	 */
	init: function () {
		document.addEventListener('DOMContentLoaded', function() {
			sliderImagesContainer = document.getElementById('slider_images');
			const addImageButton = document.getElementById('add_image');
			const sliderImages  = sliderImagesContainer.querySelectorAll('.slider-image');
			const deleteImage  = sliderImagesContainer.querySelectorAll('.remove-image');
			// intialize drag and drop functionality
			// Loop through each slider image
			if(sliderImages){
				sliderImages.forEach(function(sliderImage) {
					WISJS.addDragAndDropEvents(sliderImage);
				});
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
						if (!WISJS.allowedExtensions.includes(fileExtension)) {
							alert(`Only the following image formats are allowed: ${WISJS.allowedExtensions.join(', ')}`);
							return;
						}
						if (file.size > WISJS.maxFileSize) {
							alert(`The file ${file.filename} exceeds the 2 MB size limit.`);
							return;
						}
		
						// If valid, add image block
						let timestamp = Date.now();
						MultipleImageCnt = MultipleImageCnt + 1
						timestamp = timestamp + MultipleImageCnt;
						WISJS.addImageBlock(file.url,timestamp);
					});
				}).open();
			});	
			
			// Remove Image Event
			WISJS.deleteImage();
		});
	},
	/**
	 * Add an image block to the slider images container.
	 * 
	 * @param {string} url - The URL of the image.
	 * @param {string} [title=''] - The title of the image.
	 * @param {string} [description=''] - The description of the image.
	 * @param {string} [ctaText=''] - The CTA button text.
	 * @param {string} [ctaUrl=''] - The CTA button URL.
	 */
	addImageBlock: function(url, timestamp = '', title = '', description = '', ctaText = '', ctaUrl = '') {
		const sliderImagesContainer = document.getElementById('slider_images');
		

		// Create the image container div with draggable attribute
		const imageDiv = document.createElement('div');
		imageDiv.classList.add('slider-image');
		imageDiv.setAttribute('draggable', 'true');
		imageDiv.dataset.timestamp = timestamp;

		// Hidden input for the image URL
		const urlInput = document.createElement('input');
		urlInput.type = 'hidden';
		urlInput.name = `wis_slider_options[slides][${timestamp}][url]`;
		urlInput.value = url;

		// Displayed image preview
		const img = document.createElement('img');
		img.src = url;
		img.width = 100;

		// Title input
		const titleInput = document.createElement('input');
		titleInput.type = 'text';
		titleInput.name = `wis_slider_options[slides][${timestamp}][title]`;
		titleInput.placeholder = 'Title';
		titleInput.value = title;

		// Description input
		const descriptionInput = document.createElement('textarea');
		descriptionInput.name = `wis_slider_options[slides][${timestamp}][description]`;
		descriptionInput.placeholder = 'Description';
		descriptionInput.value = description;
		descriptionInput.rows = 4; // Set number of visible rows
		descriptionInput.cols = 50; // Set width (optional)

		// CTA Button Name input
		const ctaTextInput = document.createElement('input');
		ctaTextInput.type = 'text';
		ctaTextInput.name = `wis_slider_options[slides][${timestamp}][cta_text]`;
		ctaTextInput.placeholder = 'CTA Button Name';
		ctaTextInput.value = ctaText;

		// CTA URL input
		const ctaUrlInput = document.createElement('input');
		ctaUrlInput.type = 'url';
		ctaUrlInput.name = `wis_slider_options[slides][${timestamp}][cta_url]`;
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
		WISJS.addDragAndDropEvents(imageDiv);
	},
	/**
	 * Adds drag-and-drop event listeners to the specified image element.
	 *
	 * @method addDragAndDropEvents
	 * @memberof WISJS
	 * @instance
	 *
	 * Attaches event listeners for 'dragstart', 'dragover', 'drop', and 'dragend' 
	 * events to the provided image element, allowing it to be draggable within the 
	 * slider. The function uses WISJS methods to handle the respective events.
	 *
	 * @param {HTMLElement} imageDiv - The image element to which the drag-and-drop 
	 * events are attached.
	 */
	addDragAndDropEvents: function(imageDiv) {
		imageDiv.addEventListener('dragstart', WISJS.dragStart);
		imageDiv.addEventListener('dragover', WISJS.dragOver);
		imageDiv.addEventListener('drop', WISJS.drop);
		imageDiv.addEventListener('dragend', WISJS.dragEnd);
	},
	/**
	 * Handles the dragstart event for the draggable image elements.
	 * The event is set to allow 'move' as the drag effect, and the element is given the class 'dragging'.
	 * The dragged element is stored in the `draggedElement` variable.
	 * @param {Event} event - The dragstart event.
	 */
	dragStart: function(event) {
		draggedElement = this;
		event.dataTransfer.effectAllowed = 'move';
		this.classList.add('dragging');
		console.log(draggedElement);
	},
	/**
	 * Event handler for the dragover event.
	 * The event is prevented from its default action, and the dropEffect is set to 'move'.
	 * The target element is given a top or bottom border depending on the Y position of
	 * the mouse relative to the middle of the element. This is a visual indication of
	 * where the element will be inserted when it is dropped.
	 *
	 * @param {Event} event - The dragover event.
	 */
	dragOver: function(event) {
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
	},
	/**
	 * Handles the drop event for draggable image elements.
	 *
	 * @method drop
	 * @memberof WISJS
	 * @instance
	 *
	 * Prevents the default drop behavior and repositions the dragged element
	 * based on the drop location relative to the bounding box of the target element.
	 * Adds the dragged element before or after the target element depending on
	 * the vertical drop position.
	 *
	 * @param {Event} event - The drop event object.
	 */
	drop: function(event) {
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
	},
	/**
	 * Handles the end of a drag event.
	 *
	 * @method dragEnd
	 * @memberof WISJS
	 * @instance
	 *
	 * Removes the "dragging" class from the element and resets the CSS
	 * borders on all elements in the container.
	 */
	dragEnd: function() {
		this.classList.remove('dragging');
		sliderImagesContainer.querySelectorAll('.slider-image').forEach(el => {
			el.style['border-top'] = '';
			el.style['border-bottom'] = '';
		});
	},
	deleteImage: function(event) {
		// Select all remove buttons within the slider_images div
		const removeButtons = document.querySelectorAll('#slider_images .remove-image');

		// Loop through each button and attach a click event listener
		removeButtons.forEach(function(button) {
			button.addEventListener('click', function() {
				// Find the closest parent slider-image div and remove it from the DOM
				const sliderImage = button.closest('.slider-image');
				if (sliderImage) {
					sliderImage.remove();
				}
			});
		});
	}
};
WISJS.init();