window.onload = () =>
{
	console.clear();

	var url_string = window.location.href,
      url = new URL(url_string);
      param_img = url.searchParams.get("image"),
      param_order = url.searchParams.get("order_id");

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

		const _addZoneButton = document.querySelector( '#addZoneButton' );
			  _addZoneButton.onclick = () => _signals.addZoneRequested.dispatch();


		const _zoomInputRange = document.querySelector( '#zoomInputRange' );
			  _zoomInputRange.onchange = _zoomInputRange.oninput = () => _signals.zoomRequested.dispatch( _zoomInputRange.valueAsNumber );

		const _resetButton = document.querySelector( '#resetButton' );
			  _resetButton.onclick = () => _signals.resetRequested.dispatch();

		const _saveDataButton = document.querySelector( '#saveDataButton' );
			  _saveDataButton.onclick = () => _signals.saveDataRequested.dispatch();

		const _orientationGroup = [];
		const _quantityGroup = [];

		const getOrientation = () =>
		{
			for( let i = 0; i < _orientationGroup.length; i++ )
			{
				if( _orientationGroup[ i ].checked )
					return _orientationGroup[ i ].value;
			}

			return '';
		};

		const getQty = () =>
		{
			for( let i = 0; i < _quantityGroup.length; i++ )
			{
				if( _quantityGroup[ i ].checked )
					return parseInt( _quantityGroup[ i ].value );
			}

			return -1;
		};

		const onOrientationChanged = () => _signals.changeOrientationRequested.dispatch( getOrientation() );
		const onQtyChanged = () => _signals.changeQtyRequested.dispatch( getQty() );

		document.querySelectorAll( 'input[name=orientation]' ).forEach( radio =>
		{
			radio.onchange = onOrientationChanged;

			_orientationGroup.push( radio );
		} );

		document.querySelectorAll( 'input[name=qty]' ).forEach( radio =>
		{
			radio.onchange = onQtyChanged;

			_quantityGroup.push( radio );
		} );


		return {
			signals:_signals,
			getQty,
			getOrientation,
			setAddButtonEnabled:( value ) =>
			{
				_addZoneButton.style.display = value ? '' : 'none';
			},
		}
	};


	loadFrame( { frameURL:'../../uploads/images/' + param_img }, frame =>
	{
		let ui = UI();

		let textureFactory = TextureFactory
		( {
			domContainerID:'#svgContainer',
			width:frame.width,
			height:frame.height,
			source:frame.source,
			mask:frame.mask,
			orientation:ui.getOrientation(),
			quantity:ui.getQty(),
		} );

		textureFactory.signals.zoneAdded.add( () =>
		{
			//console.log( 'canAddZone:' + textureFactory.canAddZone() );
			ui.setAddButtonEnabled( textureFactory.canAddZone() );
		} );

		textureFactory.signals.zoneRemoved.add( () =>
		{
			//console.log( 'canAddZone:' + textureFactory.canAddZone() );
			ui.setAddButtonEnabled( true );
		} );

		textureFactory.setZoom( 0.75 );

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
			console.log( JSON.stringify( textureFactory.toJSON()));
			const request = new XMLHttpRequest();
      const url = "../d26386b04e.php";
      const params = "event=save_order_data" + "&data=" + btoa(JSON.stringify( textureFactory.toJSON() )) + '&order_id=' + param_order;
      request.open("POST", url, true);
      request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      request.addEventListener("readystatechange", () => {
	      if(request.readyState === 4 && request.status === 200) {
	        console.log(request.responseText);
		      window.location.href = '../ipad_vegas_list.php?status=1';
	      }
      });
      request.send(params);
		});

	} );
};