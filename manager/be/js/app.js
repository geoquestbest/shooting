window.onload = () =>
{
	console.clear();

	let url_string = window.location.href,
      url = new URL(url_string);
      param_img = url.searchParams.get("image"),
      param_order = url.searchParams.get("order_id"),
      param_data = url.searchParams.get("data");

	const loadFrame = ( { frameURL }, onComplete ) =>
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

				onComplete( frameData );
			};
	};

	const UI = () =>
	{
		const Signal = signals.Signal;

		const _signals =
		{
			changeOrientationRequested:new Signal(),
			changeQtyRequested:new Signal(),
			addZoneRequested:new Signal(),
			zoomRequested:new Signal(),
			resetRequested:new Signal(),
			saveDataRequested:new Signal(),
		};
		const orientationRadioGroup = [];
		const quantityRadioGroup = [];
		
		const _addZoneButton = document.querySelector( '#addZoneButton' );
		const _zoomInputRange = document.querySelector( '#zoomInputRange' );
		const _resetButton = document.querySelector( '#resetButton' );
		const _saveDataButton = document.querySelector( '#saveDataButton' );
		
		_addZoneButton.onclick = () => _signals.addZoneRequested.dispatch();
		_zoomInputRange.onchange = _zoomInputRange.oninput = () => _signals.zoomRequested.dispatch( _zoomInputRange.valueAsNumber );
		_resetButton.onclick = () => _signals.resetRequested.dispatch();
		_saveDataButton.onclick = () => _signals.saveDataRequested.dispatch();


		const getOrientation = () =>
		{
			for( let i = 0; i < orientationRadioGroup.length; i++ )
			{
				if( orientationRadioGroup[ i ].checked )
				{
					return orientationRadioGroup[ i ].value;
				}
			}
				
			return '';
		};

		const getQty = () =>
		{
			for( let i = 0; i < quantityRadioGroup.length; i++ )
			{
				if( quantityRadioGroup[ i ].checked )
				{
					return +quantityRadioGroup[ i ].value;
				}
			}

			return -1;
		};

		const setQty = ( value ) =>
		{
			for( let i = 0; i < quantityRadioGroup.length; i++ )
			{
				if( quantityRadioGroup[ i ].value == value )
				{
					quantityRadioGroup[ i ].checked = true;
					break;
				}
			}
		};
		
		const setOrientation = ( value ) =>
		{
			for( let i = 0; i < orientationRadioGroup.length; i++ )
			{
				if( orientationRadioGroup[ i ].value == value )
				{
					orientationRadioGroup[ i ].checked = true;
					break;
				}
			}
		};
		
		const setZoom = ( value ) => _zoomInputRange.value = value;
		const onOrientationChanged = () => _signals.changeOrientationRequested.dispatch( getOrientation() );
		const onQtyChanged = () => _signals.changeQtyRequested.dispatch( getQty() );

		document.querySelectorAll( 'input[name=orientation]' ).forEach( radio =>
		{
			radio.onchange = onOrientationChanged;
			orientationRadioGroup.push( radio );
		} );

		document.querySelectorAll( 'input[name=qty]' ).forEach( radio =>
		{
			radio.onchange = onQtyChanged;
			quantityRadioGroup.push( radio );
		} );


		return {
			signals:_signals,
			getQty,
			getOrientation,
			setQty,
			setOrientation,
			setZoom,
			setAddButtonEnabled:( value ) => _addZoneButton.style.display = value ? '' : 'none',
		}
	};


	loadFrame( { frameURL:'../../uploads/images/' + param_img }, frame =>
	{
		let ui = UI();

		let importedData = param_data ? atob(param_data) : '';

		console.log( param_data, importedData);

		const textureFactory = TextureFactory
		( {
			domContainerID:'#svgContainer',
			width:frame.width,
			height:frame.height,
			source:frame.source,
			mask:frame.mask,
			orientation:ui.getOrientation(),
			quantity:ui.getQty(),
		} );

		textureFactory.signals.zoneAdded.add( () => ui.setAddButtonEnabled( textureFactory.canAddZone() ) );
		textureFactory.signals.zoneRemoved.add( () => ui.setAddButtonEnabled( textureFactory.canAddZone() ) );
		textureFactory.signals.zoomChanged.add( zoom => ui.setZoom( zoom ) );
		textureFactory.signals.maxZonesChanged.add( maxZones => ui.setQty( maxZones ) );
		textureFactory.signals.orientationChanged.add( orientation => ui.setOrientation( orientation ) );		
		textureFactory.fitToScreen();

		ui.signals.changeOrientationRequested.add( orientation => textureFactory.setOrientation( orientation ) );
		ui.signals.changeQtyRequested.add( quantity =>
		{
			textureFactory.setQty( quantity );
			ui.setAddButtonEnabled( textureFactory.canAddZone() );
		} );
		
		ui.signals.addZoneRequested.add( () => textureFactory.addZone() );
		ui.signals.zoomRequested.add( zoom => textureFactory.setZoom( zoom ) );
		ui.signals.resetRequested.add( () => textureFactory.reset() );
		ui.signals.saveDataRequested.add( () => {
			//console.log( JSON.stringify( textureFactory.toJSON()));
			const request = new XMLHttpRequest();
			  const url = "../d26386b04e.php";
			  const params = "event=save_order_data" + "&data=" + btoa(JSON.stringify( textureFactory.toJSON() )) + '&order_id=' + param_order;
			  request.open("POST", url, true);
			  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			  request.addEventListener("readystatechange", () => {
				  if(request.readyState === 4 && request.status === 200) {
					console.log(request.responseText);
				  alert('save');
					  window.location.href = '../ipad_list.php?status=1';
				  }
			  });
			  request.send(params);
		});
		
		if( importedData )
			textureFactory.fromJSON( importedData );

	} );
};