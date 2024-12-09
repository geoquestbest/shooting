window.onload = () =>
{
	console.clear();
    var url_string = window.location.href,
      url = new URL(url_string);
      param_img = url.searchParams.get("image"),
      param_simple_img = url.searchParams.get("sample_image"),
      param_order = url.searchParams.get("order_id"),
      param_json = url.searchParams.get("json");

	const loadLogos = ( namedURLs, onComplete ) =>
	{
		const logos = {};
		const createCanvasData = ( image ) =>
		{
			const width = image.naturalWidth;
			const height = image.naturalHeight;

			const canvas = document.createElement( 'canvas' );
				  canvas.width = width;
				  canvas.height = height;

			const context = canvas.getContext( '2d' );
				  context.imageSmoothingQuality = 'high';
				  context.imageSmoothingEnabled = true;
				  context.drawImage( image, 0, 0 );

			return {
				width:width,
				height:height,
				source:canvas,
			};
		};

		let loaded = 0;
		let total = 0;

		if( namedURLs != null )
		{
			for( const [ name, url ] of Object.entries( namedURLs ) )
			{
				total++;

				const image = new Image();
					  image.src = url;
					  image.onload = ( event ) =>
					  {
							logos[ name ] = createCanvasData( event.target );

							loaded++;

							if( loaded == total )
								onComplete( logos );
					  };
			}
		}

		if( total == 0 )
			onComplete( logos );
	};

	const loadFrame = ( { frameURL, sampleFrameURL }, onComplete ) =>
	{
		const frameData = {};

		let frameImage = new Image();
			frameImage.src = frameURL;
			frameImage.onload = () =>
			{
				const width = frameImage.naturalWidth;
				const height = frameImage.naturalHeight;

				const canvas = document.createElement( 'canvas' );
					  canvas.width = width;
					  canvas.height = height;

				const context = canvas.getContext( '2d' );
					  context.imageSmoothingQuality = 'high';
					  context.imageSmoothingEnabled = true;
					  context.drawImage( frameImage, 0, 0 );

				const imageData = context.getImageData( 0, 0, width, height );

				for( let i = 0; i < imageData.data.length; i += 4)
				{
					imageData.data[ i ] =
					imageData.data[ i + 1 ] =
					imageData.data[ i + 2 ] = 255;
				}

				const maskCanvas = document.createElement( 'canvas' );
					  maskCanvas.width = width;
					  maskCanvas.height = height;

				const maskContext = maskCanvas.getContext( '2d' );
					  maskContext.imageSmoothingQuality = 'high';
					  maskContext.imageSmoothingEnabled = true;
					  maskContext.putImageData( imageData, 0, 0 );

				frameData.width = width;
				frameData.height = height;
				frameData.source = canvas;
				frameData.mask = maskCanvas;

				let sampleFrameImage = new Image();
					sampleFrameImage.src = sampleFrameURL;
					sampleFrameImage.onload = () =>
					{
						const width = sampleFrameImage.naturalWidth;
						const height = sampleFrameImage.naturalHeight;

						const canvas = document.createElement( 'canvas' );
							  canvas.width = width;
							  canvas.height = height;

						const context = canvas.getContext( '2d' );
							  context.imageSmoothingQuality = 'high';
							  context.imageSmoothingEnabled = true;
							  context.drawImage( sampleFrameImage, 0, 0 );

						frameData.sample = canvas;

						onComplete( frameData );
					};

			};
	};

	const loadFonts = ( onComplete ) =>
	{
		const fontFamilyList = 
		[
			{
				name:'Roboto',
				fonts:
				[
					{ url:'fonts/Roboto-Regular.ttf', 		style:'normal', weight:'400', type:'application/x-font-ttf' },
					{ url:'fonts/Roboto-Italic.ttf', 		style:'italic', weight:'400', type:'application/x-font-ttf' },
					{ url:'fonts/Roboto-Bold.ttf', 			style:'normal', weight:'700', type:'application/x-font-ttf' },	
					{ url:'fonts/Roboto-BoldItalic.ttf', 	style:'italic', weight:'700', type:'application/x-font-ttf' },
				],
			},
			
			// 41

			{
				name:'Armin Grotesk',
				fonts:
				[
					{ url:'fonts/Armin Grotesk/ArminGrotesk-Regular.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
					{ url:'fonts/Armin Grotesk/ArminGrotesk-Italic.ttf', 	style:'italic', weight:'400', type:'application/x-font-ttf' },
					{ url:'fonts/Armin Grotesk/ArminGrotesk-UltraBold.ttf', 	style:'normal', weight:'700', type:'application/x-font-ttf' },	
					{ url:'fonts/Armin Grotesk/ArminGrotesk-UltraBoldItalic.ttf', 	style:'italic', weight:'700', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Barlow Condensed',
				fonts:
				[
					{ url:'fonts/Barlow Condensed/BarlowCondensed-Regular.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
					{ url:'fonts/Barlow Condensed/BarlowCondensed-Italic.ttf', 	style:'italic', weight:'400', type:'application/x-font-ttf' },
					{ url:'fonts/Barlow Condensed/BarlowCondensed-Bold.ttf', 	style:'normal', weight:'700', type:'application/x-font-ttf' },	
					{ url:'fonts/Barlow Condensed/BarlowCondensed-ExtraBoldItalic.ttf', 	style:'italic', weight:'700', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Baskerville',
				fonts:
				[
					{ url:'fonts/baskerville-old-face/BASKVILL.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Bebas',
				fonts:
				[
					{ url:'fonts/Bebas/Bebas-Regular.otf', 	style:'normal', weight:'400', type:'font/opentype' },
				],
			},
			{
				name:'Benedict',
				fonts:
				[
					{ url:'fonts/Benedict/Benedict.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Bertha Melanie',
				fonts:
				[
					{ url:'fonts/Bertha Melanie/BerthaMelanie.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Brown',
				fonts:
				[
					{ url:'fonts/Brown-Font/BrownMedium Regular.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
					{ url:'fonts/Brown-Font/Brown Italic.ttf', 	style:'italic', weight:'400', type:'application/x-font-ttf' },
					{ url:'fonts/Brown-Font/Brown Bold.ttf', 	style:'normal', weight:'700', type:'application/x-font-ttf' },	
					{ url:'fonts/Brown-Font/Brown BoldItalic.ttf', 	style:'italic', weight:'700', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Calibri',
				fonts:
				[
					{ url:'fonts/Calibri/Calibri Regular.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
					{ url:'fonts/Calibri/Calibri Italic.ttf', 	style:'italic', weight:'400', type:'application/x-font-ttf' },
					{ url:'fonts/Calibri/Calibri Bold.TTF', 	style:'normal', weight:'700', type:'application/x-font-ttf' },	
					{ url:'fonts/Calibri/Calibri Bold Italic.ttf', 	style:'italic', weight:'700', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Century Gothic',
				fonts:
				[
					{ url:'fonts/Century Gothic/07558_CenturyGothic.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
					{ url:'fonts/Century Gothic/07556_CenturyGothicItalic.ttf', 	style:'italic', weight:'400', type:'application/x-font-ttf' },
					{ url:'fonts/Century Gothic/07553_CenturyGothicBold.ttf', 	style:'normal', weight:'700', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Charter',
				fonts:
				[
					{ url:'fonts/Charter/Charter Regular.otf', 	style:'normal', weight:'400', type:'font/opentype' },
					{ url:'fonts/Charter/Charter Italic.otf', 	style:'italic', weight:'400', type:'font/opentype' },
					{ url:'fonts/Charter/Charter Bold.otf', 	style:'normal', weight:'700', type:'font/opentype' },	
					{ url:'fonts/Charter/Charter Bold Italic.otf', 	style:'italic', weight:'700', type:'font/opentype' },
				],
			},
			{
				name:'Chocolate',
				fonts:
				[
					{ url:'fonts/Chocolate/Chocolate.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Christmas Bell',
				fonts:
				[
					{ url:'fonts/Christmas Bell/ChristmasBell.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Christmas Day',
				fonts:
				[
					{ url:'fonts/christmas_day/Christmas Day Personal Use.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Din',
				fonts:
				[
					{ url:'fonts/Din/DIN.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
					{ url:'fonts/Din/DIN Bold.ttf', 	style:'normal', weight:'700', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Free Style',
				fonts:
				[
					{ url:'fonts/Free style/FREESCPT (1).TTF', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Futura',
				fonts:
				[
					{ url:'fonts/Futura/futura medium bt.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
					{ url:'fonts/Futura/Futura Light Italic font.ttf', 	style:'italic', weight:'400', type:'application/x-font-ttf' },
					{ url:'fonts/Futura/Futura Bold.otf', 	style:'normal', weight:'700', type:'font/opentype' },	
					{ url:'fonts/Futura/Futura Book Italic font.ttf', 	style:'italic', weight:'700', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Gabelisa',
				fonts:
				[
					{ url:'fonts/Gabelisa/Gabelisa Font by Keithzo (7NTypes).otf', 	style:'normal', weight:'400', type:'font/opentype' },
				],
			},
			{
				name:'Halloween Day',
				fonts:
				[
					{ url:'fonts/halloween_day/Halloween Day.otf', 	style:'normal', weight:'400', type:'font/opentype' },
				],
			},
			{
				name:'Hey October',
				fonts:
				[
					{ url:'fonts/Hey october/Hey October.otf', 	style:'normal', weight:'400', type:'font/opentype' },
				],
			},
			{
				name:'Ink free',
				fonts:
				[
					{ url:'fonts/Ink free/ink-free.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
				],
			},
			{
				name:'KG Summer Storm',
				fonts:
				[
					{ url:'fonts/KG SUMMER/KGSummerStormSmooth.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
				],
			},
			{
				name:'KG Red Hands',
				fonts:
				[
					{ url:'fonts/kg_red_hands/KGRedHands.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Liebe Doris',
				fonts:
				[
					{ url:'fonts/LiebeDoris/LiebeFonts_Liebe.Doris.otf', 	style:'normal', weight:'400', type:'font/opentype' },
					{ url:'fonts/LiebeDoris/LiebeFonts_Liebe.Doris.Italic.otf', 	style:'italic', weight:'400', type:'font/opentype' },
					{ url:'fonts/LiebeDoris/LiebeFonts_Liebe.Doris.Bold.otf', 	style:'normal', weight:'700', type:'font/opentype' },	
					{ url:'fonts/LiebeDoris/LiebeFonts_Liebe.Doris.Bold.Italic.otf', 	style:'italic', weight:'700', type:'font/opentype' },
				],
			},
			{
				name:'Matura',
				fonts:
				[
					{ url:'fonts/Matura/MATURASC.TTF', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Montserrat',
				fonts:
				[
					{ url:'fonts/Montserrat/Montserrat-Regular.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
					{ url:'fonts/Montserrat/Montserrat-MediumItalic.ttf', 	style:'italic', weight:'400', type:'application/x-font-ttf' },
					{ url:'fonts/Montserrat/Montserrat-Bold.ttf', 	style:'normal', weight:'700', type:'application/x-font-ttf' },	
					{ url:'fonts/Montserrat/Montserrat-BoldItalic.ttf', 	style:'italic', weight:'700', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Neon Glow',
				fonts:
				[
					{ url:'fonts/Neon Glow/NEON GLOW.otf', 	style:'normal', weight:'400', type:'font/opentype' },
					{ url:'fonts/Neon Glow/NEON GLOW-Bold.otf', 	style:'normal', weight:'700', type:'font/opentype' },	
					{ url:'fonts/Neon Glow/NEON GLOW-Bold-Italic.otf', 	style:'italic', weight:'700', type:'font/opentype' },
				],
			},
			{
				name:'Noteworthy',
				fonts:
				[
					{ url:'fonts/Noteworthy/Noteworthy-Lt.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Funny Kid',
				fonts:
				[
					{ url:'fonts/FunnyKid/FunnyKid.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Obsessed Hallowen',
				fonts:
				[
					{ url:'fonts/Obsessed Hallowen/Obsessed Halloween.otf', 	style:'normal', weight:'400', type:'font/opentype' },
				],
			},
			{
				name:'Kiddie Love',
				fonts:
				[
					{ url:'fonts/KiddieLove/Kiddie Love.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
				],
			},
			{
                        	name:'Brands Kidnapped',
				fonts:
				[
					{ url:'fonts/Brands-Kidnapped/Brands-Kidnapped.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Park Lane',
				fonts:
				[
					{ url:'fonts/Park lane/Park Lane NF.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Photograph',
				fonts:
				[
					{ url:'fonts/Photograph/Photographs.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
				],
			},
			{
				name:'kiddy',
				fonts:
				[
					{ url:'fonts/kiddy/kiddy.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Raleway',
				fonts:
				[
					{ url:'fonts/Raleway/Raleway-Regular.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
					{ url:'fonts/Raleway/Raleway-Bold.ttf', 	style:'normal', weight:'700', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Sunquish',
				fonts:
				[
					{ url:'fonts/Sunquish/Sunquish.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Telegraf',
				fonts:
				[
					{ url:'fonts/Telegraf/PPTelegraf-Regular.otf', 	style:'normal', weight:'400', type:'font/opentype' },
					{ url:'fonts/Telegraf/PPTelegraf-UltraBold.otf', 	style:'normal', weight:'700', type:'font/opentype' },
				],
			},
			{
				name:'Times New Roman',
				fonts:
				[
					{ url:'fonts/Times New Roman/times new roman.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
					{ url:'fonts/Times New Roman/times new roman italic.ttf', 	style:'italic', weight:'400', type:'application/x-font-ttf' },
					{ url:'fonts/Times New Roman/times new roman bold.ttf', 	style:'normal', weight:'700', type:'application/x-font-ttf' },	
					{ url:'fonts/Times New Roman/times new roman bold italic.ttf', 	style:'italic', weight:'700', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Trebuchet',
				fonts:
				[
					{ url:'fonts/Trebuchet/trebuc.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
					{ url:'fonts/Trebuchet/Trebuchet-MS-Italic.ttf', 	style:'italic', weight:'400', type:'application/x-font-ttf' },
				],
			},
			{
				name:'Verdana Pro',
				fonts:
				[
					{ url:'fonts/Verdana Pro/VerdanaPro-Regular.ttf', 	style:'normal', weight:'400', type:'application/x-font-ttf' },
					{ url:'fonts/Verdana Pro/VerdanaPro-Italic.ttf', 	style:'italic', weight:'400', type:'application/x-font-ttf' },
					{ url:'fonts/Verdana Pro/VerdanaPro-Bold.ttf', 	style:'normal', weight:'700', type:'application/x-font-ttf' },	
					{ url:'fonts/Verdana Pro/VerdanaPro-CondBoldItalic.ttf', 	style:'italic', weight:'700', type:'application/x-font-ttf' },
				],
			},
		];

		let loaded = 0;
		let total = 0;

		fontFamilyList.forEach( family =>
		{
			family.fonts.forEach( data =>
			{
				total++;

				fetch( data.url ).then( response =>
				{
					response.arrayBuffer().then( buffer =>
					{
						const reader = new FileReader();
							  reader.onload = () =>
							  {
									data.dataURL = reader.result;

									new FontFace( family.name, 'url("' + data.dataURL + '")', { style:data.style, weight:data.weight } ).load().then( font =>
									{
										data.font = font;
										document.fonts.add( font );

										// console.log( data.font );

										loaded++;

										if( loaded == total )
											onComplete( fontFamilyList );
									} );
							  };
							  reader.onerror = () => console.error( reader.error );
							  reader.readAsDataURL( new Blob( [ buffer ], { type:data.type } ) );

					} ).catch( error => console.error( error ) );

				} ).catch( error => console.error( error ) );
			} );
		} );
	};

	const UI = ( { textureFactory, fontFamilyList } ) =>
	{
		const Signal = signals.Signal;

		const _signals =
		{
			textAdditionRequested:new Signal(),
			zoomRequested:new Signal(),
			saveResultRequested:new Signal(),
			savePreviewRequested:new Signal(),
			savePreview2Requested:new Signal(),
			saveFrameRequested:new Signal(),
			saveDataRequested:new Signal(),
			saveSVGRequested:new Signal(),
      saveAsImagetoServerRequested:new Signal(),
		};

		const updateColorInput = ( colorInput ) =>
		{
			if( colorInput )
			{
				if( colorInput.parentElement )
					colorInput.parentElement.style.backgroundColor = colorInput.value;

				const wrapper = colorInput.closest( '.label__input-wrapp' );
				const isWrapperHidden = wrapper.classList.contains( '--hiden' );
				const isColorInputHidden = colorInput.classList.contains( 'hidden' );


				if( isColorInputHidden && !isWrapperHidden )
					wrapper.classList.add( '--hiden' );

				if( !isColorInputHidden && isWrapperHidden )
					wrapper.classList.remove( '--hiden' );
			}
		};

		const hideInfo = () =>
		{
			window.removeEventListener( 'mousedown', hideInfo );
			document.querySelector( '.box-creation-title' ).remove();
		};

		window.addEventListener( 'mousedown', hideInfo );

		const _commonTools = document.querySelector( '#commonTools' );
			  _commonTools.classList.remove( 'hidden' );

		const _addTextButton = document.querySelector( '#addTextButton' );
			  _addTextButton.onclick = () =>
			  {
					const textDialogContainer = document.querySelector( '.popup__bg' );
					const textDialog = document.querySelector( '.creation__popup-title-form' );
					const textInput = textDialog.querySelector( '.dialog-input' );
					const addButton = textDialog.querySelector( '.dialog-action-add' );
					const closeButton = textDialog.querySelector( '.dialog-action-cancel' );

					const closeDialog = () =>
					{
						addButton.onclick = null;
						closeButton.onclick = null;

						textDialogContainer.classList.remove( 'active' );
						textDialog.classList.remove( 'active' );
					};

					closeButton.onclick = closeDialog;
					addButton.onclick = () =>
					{
						if( textInput.value != '' )
						{
							closeDialog();

							_signals.textAdditionRequested.dispatch( textInput.value );
						}
						else
							textInput.focus();
					};

					textDialogContainer.classList.add( 'active' );
					textDialog.classList.add( 'active' );
			  };

		const _zoomInputRange = document.querySelector( '#zoomInputRange' );
			  _zoomInputRange.onchange = _zoomInputRange.oninput = () => _signals.zoomRequested.dispatch( _zoomInputRange.valueAsNumber );

		const _saveFrameButton = document.querySelector( '#saveFrameButton' );
			  if( _saveFrameButton )
				  _saveFrameButton.onclick = () => _signals.saveFrameRequested.dispatch();

		const _savePreviewButton = document.querySelector( '#savePreviewButton' );
			  if( _savePreviewButton )
				  _savePreviewButton.onclick = () => _signals.savePreviewRequested.dispatch();

		const _savePreview2Button = document.querySelector( '#savePreview2Button' );
			  if( _savePreview2Button )
				  _savePreview2Button.onclick = () => _signals.savePreview2Requested.dispatch();

		const _saveResultButton = document.querySelector( '#saveResultButton' );
			  if( _saveResultButton )
				  _saveResultButton.onclick = () => _signals.saveResultRequested.dispatch();

		const _saveDataButton = document.querySelector( '#saveDataButton' );
			  if( _saveDataButton )
				  _saveDataButton.onclick = () => _signals.saveDataRequested.dispatch();

		const _saveSVGButton = document.querySelector( '#saveAsSVGButton' );
			  if( _saveSVGButton )
				  _saveSVGButton.onclick = () => _signals.saveSVGRequested.dispatch();

    const _saveAsImagetoServerButton = document.querySelector( '#saveAsImagetoServerButton' );
      if( _saveAsImagetoServerButton )
          _saveAsImagetoServerButton.onclick = () => _signals.saveAsImagetoServerRequested.dispatch();

		// ----------------------------------------------------------------------
		// Логотип
		// ----------------------------------------------------------------------

		const _logoTools = document.querySelector( '#logoTools' );
		const _logoInvertedCheckbox = document.querySelector( '#logoInvertedCheckbox' );
		const _logoScaleRange = document.querySelector( '#logoScaleRange' );

		textureFactory.signals.elementSelected.add( uuid =>
		{
			const attributes = textureFactory.getElementAttributes( uuid );

			if( attributes.type != 'logo' ) return;

			_logoTools.classList.remove( 'hidden' );

			_logoInvertedCheckbox.checked = textureFactory.getLogoInverted();
			_logoInvertedCheckbox.onchange = () => textureFactory.setLogoInverted( event.target.checked );

			_logoScaleRange.value = textureFactory.getElementScale( uuid );
			_logoScaleRange.onchange = _logoScaleRange.oninput = ( event ) => textureFactory.setElementScale( uuid, event.target.value );

			const onLogoAttributesChanged = ( changedUUID, changedAttributes ) =>
			{
				if( uuid == changedUUID )
				{
					// console.log( 'elementAttributesChanged:', changedAttributes );

				}
				else
				{
					console.warn( 'elementAttributesChanged:', { uuid:uuid, changedUUID:changedUUID } );
				}
			};

			const onLogoDeselected = deselectedUUID =>
			{
				if( uuid == deselectedUUID )
				{
					// console.log( 'elementDeselected' );

					_logoTools.classList.add( 'hidden' );

					_logoInvertedCheckbox.onchange =
					_logoScaleRange.oninput =
					_logoScaleRange.onchange = null;

					textureFactory.signals.elementAttributesChanged.remove( onLogoAttributesChanged );
					textureFactory.signals.elementDeselected.remove( onLogoDeselected );
					textureFactory.signals.elementScaleChanged.remove( onLogoScaleChanged );
				}
				else
				{
					console.warn( 'elementDeselected:', { uuid:uuid, deselectedUUID:deselectedUUID } );
				}
			};

			const onLogoScaleChanged = ( changedUUID, scale ) =>
			{
				if( uuid == changedUUID )
					_logoScaleRange.value = scale;
			};

			textureFactory.signals.elementAttributesChanged.add( onLogoAttributesChanged );
			textureFactory.signals.elementDeselected.add( onLogoDeselected );
			textureFactory.signals.elementScaleChanged.add( onLogoScaleChanged );
		} );

		// ----------------------------------------------------------------------
		// Текст
		// ----------------------------------------------------------------------

		const _textTools = document.querySelector( '#textTools' );
		const _textInput = document.querySelector( '#textInput' );
		const _textFontFamilySelect = document.querySelector( '#textFontFamilySelect' );
		const _textFontStyleCheckbox = document.querySelector( '#textFontStyleCheckbox' );
		const _textFontWeightCheckbox = document.querySelector( '#textFontWeightCheckbox' );
		const _textScaleRange = document.querySelector( '#textScaleRange' );
		const _textOpacityRange = document.querySelector( '#textOpacityRange' );
		const _deleteTextButton = document.querySelector( '#deleteTextButton' );
		const _textFillGradientCheckBox = document.querySelector( '#textGradientCheckbox' );
		const _textFillColorInput = document.querySelector( '#textFillColorInput' );
		const _textFillColor2Input = document.querySelector( '#textFillColor2Input' );
		const _textShadowCheckbox = document.querySelector( '#textShadowCheckbox' );
		const _textStrokeCheckbox = document.querySelector( '#textStrokeCheckbox' );
		const _textStrokeGroup = document.querySelector( '.contour-box' );
		const _textStrokeColorInput = document.querySelector( '#textStrokeColorInput' );
		const _textStrokeWidthRange = document.querySelector( '#textStrokeWidthRange' );


		// ----------------------------------------------------------------------
		// Шрифты
		// ----------------------------------------------------------------------

			const getSelectedFontFamily = () =>
			{
				return _textFontFamilySelect.options[ _textFontFamilySelect.selectedIndex ].textContent;
			};

			const getSelectedFontStyle = () =>
			{
				return _textFontStyleCheckbox.checked ? 'italic' : 'normal';
			};

			const getSelectedFontWeight = () =>
			{
				return _textFontWeightCheckbox.checked ? '700' : '400';
			};

			const setSelectedFont = ( fontFamily, fontStyle, fontWeight ) =>
			{
				for( let i = 0; i < _textFontFamilySelect.options.length; i++ )
				{
					if( _textFontFamilySelect.options[ i ].textContent == fontFamily )
					{
						_textFontFamilySelect.selectedIndex = i;
						break;
					}
				}

				_textFontStyleCheckbox.checked = ( fontStyle == 'italic' );
				_textFontWeightCheckbox.checked = ( fontWeight == '700' );

				updateTextFontUI();
			};

			const hasStyleAndWeight = ( fontFamily, fontStyle, fontWeight ) =>
			{
				let result = false;

				fontFamilyList.forEach( family =>
				{
					if( family.name == fontFamily )
					{
						family.fonts.forEach( data =>
						{
							if( data.style == fontStyle && data.weight == fontWeight )
								result = true;
						} );
					}
				} );

				return result;
			};

			const updateTextFontUI = () =>
			{
				// console.log( 'updateTextFontUI()' );

				const fontFamily = getSelectedFontFamily();
				const hasNormal = hasStyleAndWeight( fontFamily, 'normal', '400' );
				const hasItalic = hasStyleAndWeight( fontFamily, 'italic', '400' );
				const hasBold = hasStyleAndWeight( fontFamily, 'normal', '700' );
				const hasBoldItalic = hasStyleAndWeight( fontFamily, 'italic', '700' );

				_textFontStyleCheckbox.disabled = false;
				_textFontWeightCheckbox.disabled = false;

				if( _textFontStyleCheckbox.checked )
				{
					if( _textFontWeightCheckbox.checked && !hasBoldItalic )
					{
						_textFontStyleCheckbox.checked = false;
						_textFontStyleCheckbox.disabled = true;
					}

					if( !_textFontWeightCheckbox.checked && !hasItalic )
					{
						_textFontStyleCheckbox.checked = false;
						_textFontStyleCheckbox.disabled = true;
					}
				}
				else
				{
					if( _textFontWeightCheckbox.checked && !hasBoldItalic )
						_textFontStyleCheckbox.disabled = true;

					if( !_textFontWeightCheckbox.checked && !hasItalic )
						_textFontStyleCheckbox.disabled = true;
				}

				if( _textFontWeightCheckbox.checked )
				{
					if( _textFontStyleCheckbox.checked && !hasBoldItalic )
					{
						_textFontWeightCheckbox.checked = false;
						_textFontWeightCheckbox.disabled = true;
					}

					if( !_textFontStyleCheckbox.checked && !hasBold )
					{
						_textFontWeightCheckbox.checked = false;
						_textFontWeightCheckbox.disabled = true;
					}
				}
				else
				{
					if( _textFontStyleCheckbox.checked && !hasBoldItalic )
						_textFontWeightCheckbox.disabled = true;

					if( !_textFontStyleCheckbox.checked && !hasBold )
						_textFontWeightCheckbox.disabled = true;
				}

				_textFontFamilySelect.style.fontFamily = fontFamily;
				_textFontFamilySelect.style.fontStyle = getSelectedFontStyle();
				_textFontFamilySelect.style.fontWeight = getSelectedFontWeight();
			};

			fontFamilyList.forEach( family =>
			{
				let option = document.createElement( 'option' );
					option.textContent = family.name;
					option.style.fontFamily = family.name;

				_textFontFamilySelect.appendChild( option );
			} );

			updateTextFontUI();

		// ----------------------------------------------------------------------

		textureFactory.signals.elementSelected.add( uuid =>
		{
			const attributes = textureFactory.getElementAttributes( uuid );

			if( attributes.type != 'text' ) return;

			console.log( 'elementSelected > TextElement', attributes );

			_textTools.classList.remove( 'hidden' );

			_textInput.value = attributes.text;
			_textInput.onchange = _textInput.oninput = ( event ) => textureFactory.setElementAttributes( uuid, { text:event.target.value } );

			_deleteTextButton.onclick = () => textureFactory.removeElement( uuid );

			_textScaleRange.value = textureFactory.getElementScale( uuid );
			_textScaleRange.onchange = _textScaleRange.oninput = ( event ) => textureFactory.setElementScale( uuid, event.target.value );

			_textOpacityRange.value = attributes.opacity;
			_textOpacityRange.onchange = _textOpacityRange.oninput = ( event ) => textureFactory.setElementAttributes( uuid, { opacity:event.target.value } );

			setSelectedFont( attributes.fontFamily, attributes.fontStyle, attributes.fontWeight );

			_textFontStyleCheckbox.onchange =
			_textFontWeightCheckbox.onchange =
			_textFontFamilySelect.onchange = () =>
			{
				updateTextFontUI(); /// ??

				textureFactory.setElementAttributes( uuid,
				{
					fontFamily:getSelectedFontFamily(),
					fontStyle:getSelectedFontStyle(),
					fontWeight:getSelectedFontWeight(),
				} );
			};

			_textFillGradientCheckBox.onchange = ( event ) =>
			{
				if( event.target.checked )
				{
					console.log( 'set gradient' );
					textureFactory.setElementAttributes( uuid, { fillType:'gradient' } );
				}
				else
				{
					console.log( 'set solid' );
					textureFactory.setElementAttributes( uuid, { fillType:'solid' } );
				}
			};

			_textFillColorInput.onchange = _textFillColorInput.oninput =( event ) =>
			{
				const attributes = textureFactory.getElementAttributes( uuid );

				if( attributes.fillType == 'gradient' )
					textureFactory.setElementAttributes( uuid, { fillStopColor1:event.target.value } );

				if( attributes.fillType == 'solid' )
					textureFactory.setElementAttributes( uuid, { fillColor:event.target.value } );
			};

			_textFillColor2Input.onchange = _textFillColor2Input.oninput = ( event ) =>
			{
				textureFactory.setElementAttributes( uuid, { fillStopColor2:event.target.value } );
			};

			if( attributes.fillType == 'gradient' )
			{
				_textFillGradientCheckBox.checked = true;
				_textFillColorInput.value = attributes.fillStopColor1;
				_textFillColor2Input.value = attributes.fillStopColor2;
				_textFillColor2Input.classList.remove( 'hidden' );
			}
			else
			{
				_textFillGradientCheckBox.checked = false;
				_textFillColorInput.value = attributes.fillColor;
				_textFillColor2Input.classList.add( 'hidden' );
			}

			updateColorInput( _textFillColorInput );
			updateColorInput( _textFillColor2Input );

			_textShadowCheckbox.checked = attributes.shadowEnabled;
			_textShadowCheckbox.onchange = ( event ) =>
			{
				textureFactory.setElementAttributes( uuid, { shadowEnabled:event.target.checked } );
			};

			_textStrokeCheckbox.onchange = ( event ) => textureFactory.setElementAttributes( uuid, { stroke:event.target.checked ? _textStrokeColorInput.value : 'none' } );
			_textStrokeColorInput.onchange = _textStrokeColorInput.oninput = ( event ) => textureFactory.setElementAttributes( uuid, { stroke:event.target.value } );
			_textStrokeWidthRange.value = parseFloat( attributes.strokeWidth );
			_textStrokeWidthRange.onchange = _textStrokeWidthRange.oninput = ( event ) => textureFactory.setElementAttributes( uuid, { strokeWidth:event.target.value } );

			if( attributes.stroke == 'none' )
			{
				_textStrokeCheckbox.checked = false;
				_textStrokeGroup.classList.add( 'hidden' );
				//_textStrokeColorInput.classList.add( 'hidden' );
				//_textStrokeWidthRange.classList.add( 'hidden' );
			}
			else
			{
				_textStrokeCheckbox.checked = true;
				_textStrokeGroup.classList.remove( 'hidden' );
				//_textStrokeColorInput.classList.remove( 'hidden' );
				_textStrokeColorInput.value = attributes.stroke;
				//_textStrokeWidthRange.classList.remove( 'hidden' );
			}

			updateColorInput( _textStrokeColorInput );

			const onTextAttributesChanged = ( changedUUID, changedAttributes ) =>
			{
				if( uuid == changedUUID )
				{
					// console.log( 'elementAttributesChanged:', changedAttributes );

					if( changedAttributes.hasOwnProperty( 'opacity' ) )
						_textOpacityRange.value = changedAttributes.opacity;

					if( changedAttributes.hasOwnProperty( 'fillColor' ) )
						_textFillColorInput.value = changedAttributes.fillColor;

					if( changedAttributes.hasOwnProperty( 'fillStopColor1' ) )
						_textFillColorInput.value = changedAttributes.fillStopColor1;

					if( changedAttributes.hasOwnProperty( 'fillStopColor2' ) )
						_textFillColor2Input.value = changedAttributes.fillStopColor2;

					if( changedAttributes.hasOwnProperty( 'fillType' ) )
					{
						const attributes = textureFactory.getElementAttributes( uuid );

						if( changedAttributes.fillType == 'gradient' )
						{
							_textFillColorInput.value = attributes.fillStopColor1;
							_textFillColor2Input.value = attributes.fillStopColor2;
							_textFillColor2Input.classList.remove( 'hidden' );
						}

						if( changedAttributes.fillType == 'solid' )
						{
							_textFillColorInput.value = attributes.fillColor;
							_textFillColor2Input.classList.add( 'hidden' );
						}
					}

					updateColorInput( _textFillColorInput );
					updateColorInput( _textFillColor2Input );

					if( changedAttributes.hasOwnProperty( 'stroke' ) )
					{
						if( changedAttributes.stroke == 'none' )
						{
							_textStrokeCheckbox.checked = false;
							_textStrokeGroup.classList.add( 'hidden' );
							//_textStrokeColorInput.classList.add( 'hidden' );
							//_textStrokeWidthRange.classList.add( 'hidden' );
						}
						else
						{
							_textStrokeCheckbox.checked = true;
							_textStrokeGroup.classList.remove( 'hidden' );
							//_textStrokeColorInput.classList.remove( 'hidden' );
							_textStrokeColorInput.value = changedAttributes.stroke;
							//_textStrokeWidthRange.classList.remove( 'hidden' );
						}

						updateColorInput( _textStrokeColorInput );
					}

					if( changedAttributes.hasOwnProperty( 'strokeWidth' ) )
						_textStrokeWidthRange.value = parseFloat( changedAttributes.strokeWidth );

					if( changedAttributes.hasOwnProperty( 'fontFamily' ) ||
						changedAttributes.hasOwnProperty( 'fontStyle' ) ||
						changedAttributes.hasOwnProperty( 'fontWeight ' ) )
					{
						const attributes = textureFactory.getElementAttributes( uuid );

						// console.log( attributes.fontFamily, attributes.fontStyle, attributes.fontWeight );
						setSelectedFont( attributes.fontFamily, attributes.fontStyle, attributes.fontWeight );
					}
				}
				else
				{
					console.warn( 'elementAttributesChanged:', { uuid:uuid, changedUUID:changedUUID } );
				}
			};

			const onTextDeselected = deselectedUUID =>
			{
				if( uuid == deselectedUUID )
				{
					// console.log( 'elementDeselected' );

					_textTools.classList.add( 'hidden' );

					_deleteTextButton.onclick =
					_textInput.onchange =
					_textInput.oninput =
					_textFontFamilySelect.onchange =
					_textFontStyleCheckbox.onchange =
					_textFontWeightCheckbox.onchange =
					_textScaleRange.onchange =
					_textScaleRange.oninput =
					_textOpacityRange.onchange =
					_textOpacityRange.oninput =
					_textFillGradientCheckBox.onchange =
					_textFillColorInput.onchange =
					_textFillColorInput.oninput =
					_textFillColor2Input.onchange =
					_textFillColor2Input.oninput =
					_textStrokeCheckbox.onchange =
					_textStrokeColorInput.onchange =
					_textStrokeColorInput.oninput =
					_textStrokeWidthRange.onchange =
					_textStrokeWidthRange.oninput = null;

					textureFactory.signals.elementAttributesChanged.remove( onTextAttributesChanged );
					textureFactory.signals.elementDeselected.remove( onTextDeselected );
					textureFactory.signals.elementScaleChanged.remove( onTextScaleChanged );
				}
				else
				{
					console.warn( 'elementDeselected:', { uuid:uuid, deselectedUUID:deselectedUUID } );
				}
			};

			const onTextScaleChanged = ( changedUUID, scale ) =>
			{
				if( uuid == changedUUID )
					_textScaleRange.value = scale;
			};

			textureFactory.signals.elementAttributesChanged.add( onTextAttributesChanged );
			textureFactory.signals.elementDeselected.add( onTextDeselected );
			textureFactory.signals.elementScaleChanged.add( onTextScaleChanged );
		} );


		// ----------------------------------------------------------------------

		return {
			signals:_signals,
			getSelectedFontFamily,
			getSelectedFontStyle,
			getSelectedFontWeight,
		}
	};

	loadLogos( { white:'logo_white.png', black:'logo_black.png' }, logos =>
	{
		//console.log( logos );

		loadFrame( { frameURL:'../../uploads/images/' + param_img, sampleFrameURL:'../../uploads/images/' + param_simple_img }, frame =>
		{
			loadFonts( fontFamilyList =>
			{
        let tmp_json = param_json != '' ? atob(param_json) : '{"objects": []}';
        if (+param_order > 0) {
          logos = [];
        }
				let textureFactory = TextureFactory
				( {
					domContainerID:'#svgContainer',
					logos:logos,
					fontFamilyList:fontFamilyList,
					width:frame.width,
					height:frame.height,
					source:frame.source,
					sample:frame.sample,
					mask:frame.mask,
					importedData: JSON.parse(tmp_json),
				} );

				let ui = UI
				( {
					textureFactory:textureFactory,
					fontFamilyList:fontFamilyList,
				} );

				ui.signals.textAdditionRequested.add( text =>
				{
					textureFactory.setPlacementPointSelectionMode( true );

					const onDocMouseDown = ( event ) =>
					{
						textureFactory.addTextToClientXY( text, event.clientX, event.clientY,
						{
							fontFamily:ui.getSelectedFontFamily(),
							fontStyle:ui.getSelectedFontStyle(),
							fontWeight:ui.getSelectedFontWeight(),
						} );

						textureFactory.setPlacementPointSelectionMode( false );
						document.removeEventListener( 'mousedown', onDocMouseDown );
					};

					document.addEventListener( 'mousedown', onDocMouseDown );
				} );

				ui.signals.zoomRequested.add( zoom => textureFactory.setZoom( zoom ) );

				// ui.signals.saveSVGRequested.add( () => saveAs( new Blob( [ textureFactory.toString() ] , { type:'text/plain;charset=utf-8' } ), 'result.svg' ) );

				const dataURLtoBlob = ( dataURL ) =>
				{
					const byteString = atob( dataURL.split( ',' )[ 1 ] );
					const mimeString = dataURL.split( ',' )[ 0 ].split( ':' )[ 1 ].split( ';' )[ 0 ];
					const arrayBuffer = new ArrayBuffer( byteString.length );
					const uint8Array = new Uint8Array( arrayBuffer );

					for( let i = 0; i < byteString.length; i++ )
						uint8Array[ i ] = byteString.charCodeAt( i );

					return new Blob( [ arrayBuffer ], { type:mimeString } );
				};

				ui.signals.saveFrameRequested.add( () =>
				{
					textureFactory.getFrameDataURL( dataURL =>
					{
						saveAs( dataURLtoBlob( dataURL ), 'frame.png' );
					} );
				} );

				ui.signals.savePreviewRequested.add( () =>
				{
					textureFactory.getPreviewDataURL( dataURL =>
					{
						saveAs( dataURLtoBlob( dataURL ), 'preview.png' );
					} );
				} );

				ui.signals.savePreview2Requested.add( () =>
				{
					textureFactory.getPreview2DataURL( dataURL =>
					{
						saveAs( dataURLtoBlob( dataURL ), 'preview2.png' );
					} );
				} );

				ui.signals.saveResultRequested.add( () =>
				{
					textureFactory.getResultDataURL( dataURL =>
					{
						saveAs( dataURLtoBlob( dataURL ), 'result.png' );
					} );
				} );

				ui.signals.saveDataRequested.add( () => console.log( JSON.stringify( textureFactory.toJSON() ) ) );

        ui.signals.saveAsImagetoServerRequested.add( () =>
        {
            document.getElementById("saveAsImagetoServerButton").disabled = true;

            if (+param_order == 0) {

                  textureFactory.getFrameDataURL( dataURL =>
                {

                    textureFactory.getPreviewDataURL( dataURL2 =>
                  {

                        textureFactory.getPreview2DataURL( dataURL3 =>
                    {

                      const request = new XMLHttpRequest();
                      const url = "../d26386b04e.php";
                      const params = "event=save_image" + "&data=" + dataURL + "&data2=" + dataURL2 + "&data3=" + dataURL3 + "&json=" + btoa(JSON.stringify( textureFactory.toJSON() )) + '&image=' + param_img + '&order_id=' + param_order;
                      request.open("POST", url, true);
                      request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                      request.addEventListener("readystatechange", () => {
                        if(request.readyState === 4 && request.status === 200) {
                          console.log(request.responseText);
                          if (+param_order == 0) {
                            window.location.href = '../templates_list.php';
                          } else {
                            window.location.href = 'https://shootnbox.fr/';
                          }
                        }
                      });
                      request.send(params);
                    } );
                  } );
                } );
              } else {

                const textDialogContainer = document.querySelector( '.popup__bg' );
                const textDialog = document.querySelector( '.creation__popup' );
                textDialogContainer.classList.add( 'active' );
                textDialog.classList.add( 'active' );

                const closeButton = textDialog.querySelector( '.dialog-action-cancel' );
                const saveButton = textDialog.querySelector( '.dialog-action-save' );

                const closeDialog = () =>
                {
                  closeButton.onclick = null;

                  textDialogContainer.classList.remove( 'active' );
                  textDialog.classList.remove( 'active' );
                  document.getElementById("saveAsImagetoServerButton").disabled = false;
                };

                closeButton.onclick = closeDialog;

                const saveDialog = () =>
                {
                  saveButton.onclick = null;

                  textDialogContainer.classList.remove( 'active' );
                  textDialog.classList.remove( 'active' );

                  textureFactory.getResultDataURL( dataURL =>
                  {
                    const request = new XMLHttpRequest();
                      const url = "../d26386b04e.php";
                      const params = "event=save_image" + "&data=" + dataURL + '&order_id=' + param_order;
                      request.open("POST", url, true);
                      request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                      request.addEventListener("readystatechange", () => {
                        if(request.readyState === 4 && request.status === 200) {
                          console.log(request.responseText);
                          if (+param_order == 0) {
                            window.location.href = '../templates_list.php';
                          } else {
                            window.location.href = 'https://shootnbox.fr/template/valiadation.php';
                          }
                        }
                      });
                      request.send(params);
                });
                };

                saveButton.onclick = saveDialog;


              }
        } );
			} );
		} );
	} );

};