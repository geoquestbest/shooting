const TextureFactory = ( { domContainerID, logos, fontFamilyList, width, height, source, mask, sample, importedData } ) =>
{
	const domContainer = document.querySelector( domContainerID );
	
	const DRAW_MODE_FRAME = 1;
	const DRAW_MODE_PREVIEW = 2;
	const DRAW_MODE_PREVIEW_WITHOUT_LOGO = 3;
	const DRAW_MODE_RESULT = 4;
	
	const HANDLE_SIZE = 24;
	const HANDLE_ROTATE_URL = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAtJJREFUeNrUWk1W2zAQltPucV/Z2zz2DZQDxIWu8x6wLyk3gBMUOEALXdMAF4AbNJwgMRdIcoIkJzD5DAYhS7Ilj0I8783Gia3v0/xoZmzGXqUz1/5ckyXX/jPWN9KtAXBRu/zOJzXV1BIvbhNFUTIcDpNlFWADRsGdXhm5AH/y6yQZ9AekJHjMDT4QwjBkLmRn+zuLBzHJs0SMDbYAmU6npCQWQuC+d5/qeDx2TuLFn6rI3e1dcvjzMFlfW08+eB+Vit8nk0mltYRMZE8AQBCknz+takGL+nVzqxIJEgK9/73C3dbpn9/n70fg+OjYGjgUrvZuLoTFy7gI/gf3Eq101b2ufBZYE9DtPIDCLUTf3v62QwremgCyjAo8iKmCMiNABd6KAMDJMg2uFQEDAUrwVgRUrgOrFNcuI/L6yogAdl8GHgHqUnTPNyKAwJQFrCvBhiGL6SxsRIDPItTZRAded14YEZAFrivhwessrewHZBWlKK2o5azsns0rVl5GozF9Od1sfnFGoLmxkbtWVH5rCQwkNwdB6I6AZHOmglWMCMxm+Zt9f4Utk2gJtFp5f4/j2BmYrHtz2lLaLFJWZC5bJmko06jsFHZ1iJmsVTqN+r4/zwzNXGqTpdeqcn11UyorGbtQJDHh6ekZOYG/Fxe5a+12u/pUAtWkbSVqUriZnPjG5bSsjcQCFCNDVaNEVo3qrFCVBCYbqkZJN3axaillZs4Ws3EnWZle1j2tm/q93X3loii7saNFglJcN09C92fSkXkZC/bEoPKQNgyD9PAJgiA9yXEPTu84fkjTr662Oej8YJf/Lguzjud59rNR+KbOEosYdpGMFlUxYao2MUQ23EV2KjOp0+26zZCXjABPBMEntoSqsaNsgmdLwCiIy76NQZBnwcuX5VTtKB/E5AQWITyBBqu5NN6WyqOlByzDWPsX3bX/1KD2H3vU+nObRwEGAJLv5YOtgiqoAAAAAElFTkSuQmCC';
	const HANDLE_DELETE_URL = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAjlJREFUeNrsWoGNwjAMTNEPABswAhsAG8AGZYMyAWICRqiYADYoGxQmgBG6QT8XkWKi55+kTkn0tRRRUhR8sR2f3QrxkFSOUo468FHedX2SPALFzZHTna8jHcoSjdvMZrP6er3WoQp0g46GOz0Qhaw8BUF1Tu4XSuR9EYMkSdJcD0Tk8uVj0fP5LKqqepqbTCZiOBx6AdH4U1s5HA61VPLlqZFlGUscGOvyADCD69XYbDasANhi4Hg8qk+4ibSEKIriaSwWC3V/v9+HGQPa5+HrWlkqp9NJgbzdbp+LAfiwz8w6Ho/rsiz9xECe553QA2RaGwBvu5A2PVxkt9uxuwFcbLvdqk+vMYAglbvk5TwHAFv5f5kYJqZc5NMysHGdLsT2f6zYKHZ/Pp+rawQyAppDlsulyiNpmgqZqYU8Tt9mo9ZUQv8WvIdLbNdsRSW0icE4qWVGo5FYr9e/zuEoxhx2nMtNrQH85DZQFi5ggjLnAABzmjd1GsShyscBmIVPdACoi/11+rACuFwu7GA6ATCdTllM3wdxD4CxDmjDtZwBcNe2rrzKORP7KM47AdAVre6D2GdhQu+1TYitGltIZlAmyzL1nTa0UJzgPm0AIH5QsNCA1QlRJ0ivjS2zB1oURetiRoKx7pk69YUoX8GuY+dWq5UTf6G7r8lcm3Wsu9PcXTpYwbU77fyICRnUtov2yqKIF6tOBCnq+2dkQeWBUOiBLQeL/kF3KiJ/1QAS9cseTfYXEb5u8y3AAODTUeBi9yhEAAAAAElFTkSuQmCC';
	const HANDLE_SCALE_URL = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAb9JREFUeNrsmo2xRDAQx9ebV4ASlKAEOqADnaADHShFCXRAB3TgZTPH5ZzLSC5i88bO7Iw5w/3+sh9JAHhaxrxlPhP39sH6YrUD4FuvxSc/O+p8JNawiaJo7vt+pmrIhoybcHoqogwvihCZvccBN3YeXDDP89bjH3Dcfm3+2TRNUFWV0jVJkkAYhjQEdF0HZVkqXycTcIeQigVBAKwMfgwvHCHyApqm2YWP41jrnpeH0AKv8/QvF7AH7/s+HynyAj7BY4iRFyCDl5VMEgJMwlsXYBreqoAz4K0JOAt+nUUvfpbVdf0yh2fwc9u20jk/E8d9HMe385tV2fkCEII96UPwR8y6gEVEnudGVn33ioySGRUwDAMURcGrjk0zkgOYmJigeB9M2L3qYcoOJzFCLOVMlnwi/OJYOi8XgFVD3PQ6Cm9zBL7KAeys2GHFmMfOih0WOy3pJKYAry2ACryWAErwygKowSsJwCZFDV5pXwgFbLf7robXTmIq8F/tzKVpuvs768BK2yKXCJDtomGo2RTwv3en8eWCTn5YXdzcKzJKK7Jtradoe4zOv+jOwPFPDXj/AYc/9lgsAwc/t/kTYACWIYc9GL1xNwAAAABJRU5ErkJggg==';

	
	const Signal = signals.Signal;
	
	const _signals =
	{
		elementAdded:new Signal(),
		elementRemoved:new Signal(),
		elementSelected:new Signal(),
		elementDeselected:new Signal(),
		elementScaleChanged:new Signal(),
		elementAttributesChanged:new Signal(),
		actionStarted:new Signal(),
		actionFinished:new Signal(),
		logoChanged:new Signal(),
		zoomChanged:new Signal,
	};
	
	let _scale = 1.0;
	let _left = 0;
	let _top = 0;
	
	const _textureCanvas = document.createElement( 'canvas' );
		  _textureCanvas.width = width;
		  _textureCanvas.height = height;

	const _textureContext = _textureCanvas.getContext( '2d' );
		  _textureContext.imageSmoothingEnabled = true;
		  _textureContext.imageSmoothingQuality = 'high';
	
	const _paper = Snap( width, height );
		  _paper.clear();
		  _paper.attr( { 'xmlns':'http://www.w3.org/2000/svg' } );
		  _paper.attr( { 'xmlns:xlink':'http://www.w3.org/1999/xlink' } );
		  
		  _paper.append( Snap.parse( '<pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse"><rect fill="black" x="0" y="0" width="10" height="10" opacity="0.1"/><rect fill="white" x="10" y="0" width="10" height="10"/><rect fill="black" x="10" y="10" width="10" height="10" opacity="0.1"/><rect fill="white" x="0" y="10" width="10" height="10"/></pattern>' ) );
		  
		  
	
	const _shadow 			= _paper.filter( Snap.filter.shadow( 2, 2, 0, 'black', 0.6 ) ).attr( { filterUnits:'objectBoundingBox' } );
	const _root				= _paper.g().transform( 's' + _scale );
	const _pattern 			= _paper.select( 'pattern' ).toDefs();
	const _patternBg 		= _root.rect( 0, 0, width, height ).attr( { fill:_pattern, id:'background' } );
	const _background 		= _root.image( sample.toDataURL(), 0, 0, width, height ).attr( { id:'background' } );
	const _frame 			= _root.image( source.toDataURL(), 0, 0, width, height ).attr( { id:'frame' } );
	const _editor 			= _root.g().attr( { id:'editor' } );
	const _mask				= _root.rect( 25, 25, width - 50, height - 50 ).attr( { fill:'white' } );
	const _objects 			= _editor.g().attr( { id:'objects', mask:_mask } );
	const _maskBounds		= _editor.rect( 25, 25, width - 50, height - 50 ).attr( { stroke:'#e7388c', strokeWidth:1 / _scale, strokeDasharray:'10 5', fill:'none' } );
	const _logoLayer		= _editor.g().attr( { id:'logo-layer' } );
	const _hoverBounds 		= _editor.g().attr( { id:'hover-bounds' } );
	const _selectedBounds 	= _editor.g().attr( { id:'selected-bounds' } );
	const _handles 			= _editor.g().attr( { id:'handles' } );
	
	let _backgroundEnabled = true;
	let _placementPointSelectionMode = false;
	let _hoverEnabled = true;					
	let _hoverElement;				
	let _selectedElement;
	
	let _pointerX = 0;
	let _pointerY = 0;
	let _pointerDownX = 0;
	let _pointerDownY = 0;
	let _pointerIsDown = false;
	let _pointerIsOnSurface = false;
	
	let _action,
		_actionMatrix,
		_actionScale = 0,
		_actionPivotX = 0,
		_actionPivotY = 0,
		_actionAngle = 0,
		_actionDistance = 0;

	let _rotateHandle, _rotateHandle2,
		_scaleHandle, _scaleHandle2,
		_deleteHandle,
		_lockHandle, _unlockHandle;
		
	let _logo,
		_logoInverted = false;
		
	const _lut = [];

	for( let i = 0; i < 256; i ++ ) 
	{
		_lut[ i ] = ( i < 16 ? '0' : '' ) + ( i ).toString( 16 );
	}
	
	const generateUUID = ( prefix ) =>
	{
		const d0 = Math.random() * 0xffffffff | 0;
		const d1 = Math.random() * 0xffffffff | 0;
		const d2 = Math.random() * 0xffffffff | 0;
		const d3 = Math.random() * 0xffffffff | 0;
		const uuid = prefix + '-' +
				_lut[ d0 & 0xff ] + _lut[ d0 >> 8 & 0xff ] + _lut[ d0 >> 16 & 0xff ] + _lut[ d0 >> 24 & 0xff ] + '-' +
				_lut[ d1 & 0xff ] + _lut[ d1 >> 8 & 0xff ] + '-' + _lut[ d1 >> 16 & 0x0f | 0x40 ] + _lut[ d1 >> 24 & 0xff ] + '-' +
				_lut[ d2 & 0x3f | 0x80 ] + _lut[ d2 >> 8 & 0xff ] + '-' + _lut[ d2 >> 16 & 0xff ] + _lut[ d2 >> 24 & 0xff ] +
				_lut[ d3 & 0xff ] + _lut[ d3 >> 8 & 0xff ] + _lut[ d3 >> 16 & 0xff ] + _lut[ d3 >> 24 & 0xff ];

		return uuid.toUpperCase();
	};
	
	const updateElementImage = ( element ) => 
	{
		if( element.data().type == 'text' || element.data().type == 'logo' )
		{
			if( element.data().bitmapCache == null ) 
				element.data().bitmapCache = document.createElement( 'canvas' );
			
			const bbox = element.getBBox();
			const canvas = element.data().bitmapCache;
				  canvas.width = bbox.width;
				  canvas.height = bbox.height;
			
			// BEGIN_TEMP
			/* if( canvas.parentElement == null ) 
				domContainer.appendChild( canvas );*/
			// END_TEMP 
	
			const context = canvas.getContext( '2d' );
				  context.clearRect( 0, 0, bbox.width, bbox.height );

			const matrix = element.transform().localMatrix;

			context.translate( -bbox.x, -bbox.y );
			context.transform( matrix.a, matrix.b, matrix.c, matrix.d, matrix.e, matrix.f );

			if( element.data().type == 'text' )
			{
				context.textAlign = element.data().textAnchor == 'end' ? 'right' : ( element.data().textAnchor == 'middle' ? 'center' : 'left' ); //'left';
				context.textBaseline = 'alphabetic';
				context.fillStyle = 'black';
				context.font = element.data().fontStyle + ' ' + element.data().fontWeight + ' 48px ' + '"' + element.data().fontFamily + '"';
				
				const filledTSpans = element.data().filledTSpans;

				for( let i = 0; i < filledTSpans.length; i++ )
				{
					context.fillText
					( 
						filledTSpans[ i ].node.textContent, 
						parseInt( filledTSpans[ i ].attr( 'x' ) ), 
						parseFloat( filledTSpans[ i ].attr( 'dy' ) ) * 48
					);					
				}
			}
			else if( element.data().type == 'logo' )
			{
				context.drawImage
				( 
					element.data().source, 
					parseFloat( element.attr( 'x' ) ), 
					parseFloat( element.attr( 'y' ) ),
					parseFloat( element.attr( 'width' ) ), 
					parseFloat( element.attr( 'height' ) ) 
				);
			}
		}
	};
	
	const getElementByUUID = ( uuid ) => 
	{
		// _objects -> _editor
		return _editor.selectAll( '*' ).items.filter( element => {
			return element.data().uuid == uuid;
		} )[ 0 ];
	};
	
	const addHandles = ( element ) => 
	{
		const bbox = element.getBBox();
		
		_rotateHandle = _handles.image( HANDLE_ROTATE_URL, 0, 0, HANDLE_SIZE, HANDLE_SIZE );
		_rotateHandle.data().action = 'rotate'; 
		
		_rotateHandle2 = _handles.image( HANDLE_ROTATE_URL, 0, 0, HANDLE_SIZE, HANDLE_SIZE );
		_rotateHandle2.data().action = 'rotate'; 
		
		_scaleHandle = _handles.image( HANDLE_SCALE_URL, 0, 0, HANDLE_SIZE, HANDLE_SIZE );
		_scaleHandle.data().action = 'scale';
		
		_scaleHandle2 = _handles.image( HANDLE_SCALE_URL, 0, 0, HANDLE_SIZE, HANDLE_SIZE );
		_scaleHandle2.data().action = 'scale';

		
		if( element != _logo )
		{
			_deleteHandle = _handles.image( HANDLE_DELETE_URL, 0, 0, HANDLE_SIZE, HANDLE_SIZE );
			_deleteHandle.data().action = 'delete';
		}
		
		updateHandles( element );
	};
	
	const updateHandles = ( element ) => 
	{
		const bbox = element.getBBox();
		const handleSize = HANDLE_SIZE / _scale;
		
		if( _rotateHandle )
		{
			_rotateHandle.attr
			( {
				x:bbox.x + bbox.width, 
				y:bbox.y - handleSize,
				width:handleSize,
				height:handleSize,
			} );
		}
		
		if( _rotateHandle2 )
		{
			_rotateHandle2.attr
			( {
				x:bbox.x - handleSize, 
				y:bbox.y + bbox.height,
				width:handleSize,
				height:handleSize,
			} );
		}
		
		if( _scaleHandle )
		{
			_scaleHandle.attr
			( {
				x:bbox.x + bbox.width, 
				y:bbox.y + bbox.height,
				width:handleSize,
				height:handleSize,
			} );
		}
		
		if( _scaleHandle2 )
		{
			_scaleHandle2.attr
			( {
				x:bbox.x - handleSize, 
				y:bbox.y - handleSize,
				width:handleSize,
				height:handleSize,
			} );
		}
		
		if( _deleteHandle )
		{
			_deleteHandle.attr
			( {
				x:bbox.x + bbox.width / 2 - handleSize / 2, 
				y:bbox.y + bbox.height,
				width:handleSize,
				height:handleSize,
			} );
		}
	};
	
	const removeHandles = () => 
	{
		_handles.clear();
		
		_rotateHandle = null;
		_rotateHandle2 = null;
		_scaleHandle = null;
		_scaleHandle2 = null;
		_deleteHandle = null;
	};
	
	const clearSelectedBounds = () => 
	{
		_selectedBounds.clear();
	};
	
	const drawSelectedBounds = ( element ) => 
	{
		const bbox = element.getBBox();
		
		_selectedBounds.clear();
		_selectedBounds.rect( bbox.x, bbox.y, bbox.width, bbox.height );
		_selectedBounds.attr( { stroke:'black', strokeWidth:1 / _scale, fill:'none' } );
	};
	
	const clearHoverBounds = () => 
	{
		_hoverBounds.clear();
	};
	
	const drawHoverBounds = ( element ) => 
	{
		const bbox = element.getBBox();
					
		_hoverBounds.clear();
		_hoverBounds.rect( bbox.x, bbox.y, bbox.width, bbox.height ).attr
		( {
			stroke:'#000',
			strokeWidth:1 / _scale,
			strokeDasharray:'10 5',
			fill:'none',
		} ); 
	};
	
	const getElementScale = ( uuid ) => 
	{
		const element = getElementByUUID( uuid );
		
		return element ? element.transform().localMatrix.split().scalex : 1;
	};
	
	const setElementScale = ( uuid, scale ) => 
	{
		const element = getElementByUUID( uuid );
		
		if( element )
		{	
			const localMatrix = element.transform().localMatrix;
			const bbox = element.getBBox();
			
			const matrix = new Snap.Matrix();
				  matrix.scale( 1 / localMatrix.a * scale, 1 / localMatrix.d * scale, bbox.cx, bbox.cy );
				  matrix.add( localMatrix );

			element.transform( matrix );
			
			if( _selectedElement == element )
			{
				drawSelectedBounds( _selectedElement );	
				updateHandles( element );
			}
			
			updateElementImage( element ); 
			
			_signals.elementScaleChanged.dispatch( uuid, scale );
			
			return true;
		}
		
		return false;
	};
	
	const condenseNewlines = ( text ) => 
	{
		return text.replace( /(?:\r\n|\r|\n)+/g, '\n' );
	};
	
	const isValidTextAnchor = ( value ) =>
	{
		return value == 'start' || value == 'middle' || value == 'end';
	}
	
	const getRGBString = ( value ) =>
	{
		if( value < 0 ) value = 0;
		if( value > 0xFFFFFF ) value = 0xFFFFFF;

		value = Math.floor( value ).toString( 16 );
		
		while( value.length < 6 ) value = '0' + value;
		
		return '#' + value;
	};
	
	const getElementAttributes = ( uuid ) => 
	{
		const attributes = {};
		const element = getElementByUUID( uuid );
		
		if( element )
		{
			attributes.uuid = uuid;
			attributes.type = element.data().type;
			attributes.opacity = element.attr( 'opacity' );

			if( attributes.type == 'text' )
			{
				const fillType = element.data().fillType;

				if( fillType == 'solid' ) 
					attributes.fillType = fillType;
				else if( fillType == 'gradient' ) 
					attributes.fillType = fillType;
				else 
					attributes.fillType = 'none';
				
				attributes.text = element.data().text.join( '\n' );
				attributes.textAnchor = element.data().textAnchor;
				attributes.fontFamily = element.data().fontFamily;
				attributes.fontStyle = element.data().fontStyle;
				attributes.fontWeight = element.data().fontWeight;
				attributes.fillColor = element.data().solid;
				attributes.fillStopColor1 = element.data().stopColor1;
				attributes.fillStopColor2 = element.data().stopColor2;	
				attributes.shadowEnabled = element.data().shadowEnabled;
				attributes.stroke = element.data().stroke;
				attributes.strokeWidth = element.data().strokeWidth;
			}
		}
	
		return attributes;
	};
	
	const setElementAttributes = ( uuid, attributes ) => 
	{	
		const element = getElementByUUID( uuid );
		
		if( element )
		{				
			const type = element.data().type;

			// console.log( 'setElementAttributes:', type, attributes );
			
			if( type == 'logo' )
			{
				if( attributes.hasOwnProperty( 'opacity' ) )
					element.attr( { opacity:attributes.opacity } );
			}
			else if( type == 'text' )
			{		
				const elementAttributes = {};
				
				let needsUpdateElement = false;
				let needsUpdateMultilineText = false;
				let	text = element.data().text;
				
				if( attributes.hasOwnProperty( 'text' ) )
				{
					text = attributes.text;
					needsUpdateMultilineText = true;
				}

				if( isValidTextAnchor( attributes.textAnchor ) )
				{
					if( element.data().textAnchor != attributes.textAnchor )
					{
						element.data().textAnchor = attributes.textAnchor; 
						needsUpdateMultilineText = true;
					}
				}

				if( attributes.hasOwnProperty( 'fontFamily' ) )
				{
					if( element.data().fontFamily != attributes.fontFamily )
					{
						element.data().fontFamily = attributes.fontFamily;
						needsUpdateMultilineText = true;
					}
				}

				if( attributes.hasOwnProperty( 'fontStyle' ) )
				{
					if( element.data().fontStyle != attributes.fontStyle )
					{
						element.data().fontStyle = attributes.fontStyle;
						needsUpdateMultilineText = true;
					}						
				}
				
				if( attributes.hasOwnProperty( 'fontWeight' ) )
				{
					if( element.data().fontWeight != attributes.fontWeight )
					{
						element.data().fontWeight = attributes.fontWeight;
						needsUpdateMultilineText = true;
					}						
				}

				if( attributes.hasOwnProperty( 'stroke' ) )
				{
					let stroke = Snap.getRGB( attributes.stroke ).hex;
					if( element.data().stroke != stroke )
					{
						element.data().stroke = stroke;	
						needsUpdateMultilineText = true;
					}
				}

				if( attributes.hasOwnProperty( 'strokeWidth' ) )
				{
					let strokeWidth = parseInt( attributes.strokeWidth );
					if( element.data().strokeWidth != strokeWidth && !isNaN( strokeWidth ) )
					{
						element.data().strokeWidth = attributes.strokeWidth;	
						needsUpdateMultilineText = true;
					}
				}

				if( attributes.hasOwnProperty( 'fillColor' ) )
				{
					let fillColor = Snap.getRGB( attributes.fillColor ).hex;
					if( element.data().fillColor != fillColor )
					{
						element.data().solid = fillColor;
						needsUpdateMultilineText = true;
					}
				}

				if( attributes.hasOwnProperty( 'fillStopColor1' ) )
				{
					let stopColor1 = Snap.getRGB( attributes.fillStopColor1 ).hex;
					if( element.data().stopColor1 != stopColor1 )
					{
						element.data().stopColor1 = stopColor1;
						element.data().gradient.setStops( element.data().stopColor1 + '-' + element.data().stopColor2 );
						needsUpdateMultilineText = true;
					}
				}

				if( attributes.hasOwnProperty( 'fillStopColor2' ) )
				{
					let stopColor2 = Snap.getRGB( attributes.fillStopColor2 ).hex;
					if( element.data().stopColor2 != stopColor2 )
					{
						element.data().stopColor2 = stopColor2;
						element.data().gradient.setStops( element.data().stopColor1 + '-' + element.data().stopColor2 );
						needsUpdateMultilineText = true;
					}
				}
				
				if( attributes.hasOwnProperty( 'fillType' ) )
				{
					let fillType = attributes.fillType;
					if( element.data().fillType != fillType )
					{
						element.data().fillType = fillType;
						needsUpdateMultilineText = true;
					}
				}

				if( attributes.hasOwnProperty( 'shadowEnabled' ) )
				{
					element.data().shadowEnabled = attributes.shadowEnabled;
					
					if( element.data().shadowEnabled )
						elementAttributes.filter = _shadow;
					else 
						elementAttributes.filter = null;
					
					needsUpdateElement = true;
				}
				
				if( attributes.hasOwnProperty( 'opacity' ) )
				{
					elementAttributes.opacity = attributes.opacity;
					needsUpdateElement = true;
				}
				
				if( needsUpdateElement )
					element.attr( elementAttributes );
				
				if( needsUpdateMultilineText )
					updateMultilineText( element, text );
			}
			
			if( _selectedElement == element )
			{
				drawSelectedBounds( _selectedElement );	
				updateHandles( element );
			}
			
			_signals.elementAttributesChanged.dispatch( uuid, attributes );
				
			return true;
		}
		
		return false;
	};
	
	const removeSelection = () => 
	{
		const uuid = _selectedElement ? _selectedElement.data().uuid : null;
		
		_selectedElement = null;
		
		if( uuid )
		{						
			clearHoverBounds();
			clearSelectedBounds();									
			removeHandles();

			_signals.elementDeselected.dispatch( uuid );
			
			return true;
		}
		
		return false;
	};
	
	const selectElement = ( uuid ) => 
	{
		const element = getElementByUUID( uuid );
		
		if( element )
		{
			removeSelection();
			
			_selectedElement = element;

			clearHoverBounds();
			drawSelectedBounds( _selectedElement );
			addHandles( _selectedElement );

			_signals.elementSelected.dispatch( uuid );
			
			return true;
		}
		
		return false;
	};

	const removeElement = ( uuid ) => 
	{
		const element = getElementByUUID( uuid );
		
		if( element )
		{
			if( _selectedElement == element )
				removeSelection();
			
			 // BEGIN_TEMP
			/*if( element.data().bitmapCache )
			{
				if( element.data().bitmapCache.parentElement )
					element.data().bitmapCache.remove();
			}*/
			// END_TEMP 
			
			if( element.data().type == 'text' )
				disposeMultilineText( element );
			
			if( element.data().gradient )
			{
				element.data().gradient.remove();
				element.data().gradient = null;
			}
			
			element.data().filledText = null;
			element.data().strokedText = null;
			element.data().source = null;
			element.data().bitmapCache = null;
			element.remove();

			_signals.elementRemoved.dispatch( uuid );
			
			return true;
		}
		
		return false;
	};
	
	const addLogoImage = ( source, cx, cy, naturalWidth, naturalHeight ) => 
	{
		const maxSize = 64;
		
		let width = naturalWidth;
		let height = naturalHeight;
		
		if( width > maxSize || height > maxSize )
		{
			const scale = Math.max( maxSize / width, maxSize / height );
			
			width *= scale;
			height *= scale;
		}
		
		const uuid = generateUUID( 'I' );
		const element = _logoLayer.image( source.toDataURL(), cx - width / 2, cy - height / 2, width, height );
			element.attr( { class:'logo' } );
			element.data().type = 'logo';
			element.data().source = source;
			element.data().uuid = uuid;	

		updateElementImage( element );

		_signals.elementAdded.dispatch( uuid );

		return uuid;
	};
	
	const setLogoInverted = ( value ) => 
	{
		console.log( 'setLogoInverted -> ' + value );
		
		if( value != _logoInverted )
		{
			_logoInverted = value;
			
			if( _logo == null )
				return;
				
			const isSelected = _logo == _selectedElement;
			const localMatrix = _logo.transform().localMatrix;

			removeElement( _logo.data().uuid );
			
			const logo = _logoInverted ? logos.white : logos.black;
			const uuid = addLogoImage( logo.source, 0, 0, logo.width, logo.height );
			
			_logo = getElementByUUID( uuid );
			_logo.transform( new Snap.Matrix().add( localMatrix ) );
			
			if( isSelected )
				selectElement( uuid );

			_signals.logoChanged.dispatch( _logoInverted );
		}
	};

	const addLogo = () => 
	{
		if( logos == null || logos.white == null || logos.black == null )
		{
			console.warn( 'Continue without logo' );
			return;
		}
			
		const logo = _logoInverted ? logos.white : logos.black;
		const uuid = addLogoImage( logo.source, 0, 0, logo.width, logo.height );
		
		_logo = getElementByUUID( uuid );	
		
		const bbox = _logo.getBBox();
		
		_logo.transform( new Snap.Matrix().translate( bbox.width / 2 + 40, bbox.height / 2 + 40 ) );
	};
	
	const getFontData = ( fontFamily, fontStyle, fontWeight ) => 
	{
		let result = null;
		
		fontFamilyList.forEach( family => 
		{
			if( family.name == fontFamily )
			{
				family.fonts.forEach( data => 
				{
					if( data.style == fontStyle && data.weight == fontWeight )
						result = data;
				} );
			}
		} );
		
		return result;
	};
	
	const hasFont = ( fontFamily, fontStyle, fontWeight ) => 
	{
		const result = _paper.select( 'style[data-font="' + fontFamily + ' ' + fontStyle + ' ' + fontWeight + '"]' ) != null;
		
		//console.log( 'hasFont', fontFamily, fontStyle, fontWeight, result );
		
		return result;
	};
	
	const removeFont = ( fontFamily, fontStyle, fontWeight ) => 
	{
		const element = _paper.select( 'style[data-font="' + fontFamily + ' ' + fontStyle + ' ' + fontWeight + '"]' );
		
		if( element )
			element.remove();
	};
	
	const addFont = ( fontFamily, fontStyle, fontWeight ) => 
	{
		if( hasFont( fontFamily, fontStyle, fontWeight ) ) 
			return;
			
		const data = getFontData( fontFamily, fontStyle, fontWeight );
		
		if( data == null )
		{
			console.warn( 'addFont( ', fontFamily + ' ' + fontStyle + ' ' + fontWeight, ' )' );
			return;
		}
		
		const string = 	'<style data-font="' + fontFamily + ' ' + fontStyle + ' ' + fontWeight + 
						'" type="text/css">@font-face {font-family: "' + fontFamily + 
						'"; font-style: ' + fontStyle + 
						'; font-weight: ' + fontWeight + 
						'; src: url("' + data.dataURL + 
						'");}</style>';
		
		const element = Snap.parse( string ).select( 'style' );
		
		_paper.append( element );
		
		element.toDefs();
	};
		
	const addText = ( text, x, y, attributes ) => 
	{						
		let uuid = generateUUID( 'T' );
		let element = _objects.g().attr( { class:'text' } );
			element.data().type = 'text';
			element.data().uuid = uuid;
			element.data().fillType = 'solid'; // 'gradient';
			element.data().solid = '#FFFF00';
			element.data().stopColor1 = '#FF0000';
			element.data().stopColor2 = '#FFFF00';
			element.data().gradient = _paper.gradient( 'l(0,0,0,1)' + element.data().stopColor1 + '-' + element.data().stopColor2 );
			element.data().gradient.attr( { gradientUnits:'objectBoundingBox' } );
			element.data().shadowEnabled = false;
			
			if( isValidTextAnchor( attributes.textAnchor ) )
				element.data().textAnchor = attributes.textAnchor;
			else 
				element.data().textAnchor = 'middle';

			if( attributes.hasOwnProperty( 'fontStyle' ) )
				element.data().fontStyle = attributes.fontStyle;
			else 
				element.data().fontStyle = 'normal';
			
			if( attributes.hasOwnProperty( 'fontFamily' ) )
				element.data().fontWeight = attributes.fontWeight;
			else 
				element.data().fontWeight = '400';
			
			if( attributes.hasOwnProperty( 'fontFamily' ) )
				element.data().fontFamily = attributes.fontFamily;
			
			if( !hasFont( element.data().fontFamily, element.data().fontStyle, element.data().fontWeight ) )
				addFont( element.data().fontFamily, element.data().fontStyle, element.data().fontWeight );
			
			element.data().stroke = Snap.getRGB( attributes.stroke ).hex;
			element.data().strokeWidth = isNaN( parseInt( attributes.strokeWidth ) ) ? 1 : parseInt( attributes.strokeWidth );
			
			element.transform( new Snap.Matrix().translate( x, y ) );

		
		updateMultilineText( element, text );

		_signals.elementAdded.dispatch( uuid );

		return uuid;
	};

	const setPlacementPointSelectionMode = ( value ) => 
	{
		_placementPointSelectionMode = value;
		
		if( _placementPointSelectionMode )
		{
			removeSelection();
			domContainer.classList.add( 'crosshair-cursor' );
		}
		else 
		{
			domContainer.classList.remove( 'crosshair-cursor' );
		}
	};
	
	const getDistance = ( x1, y1, x2, y2 ) => 
	{
		const a = x1 - x2;
		const b = y1 - y2;

		return Math.sqrt( a * a + b * b );
	};
	
	const rotatePoint = ( cx, cy, x, y, angle ) =>
	{
		let radians = ( Math.PI / 180 ) * angle,
			cos = Math.cos( radians ),
			sin = Math.sin( radians ),
			nx = ( cos * ( x - cx ) ) + ( sin * ( y - cy ) ) + cx,
			ny = ( cos * ( y - cy ) ) - ( sin * ( x - cx ) ) + cy;
			
		return { x:nx, y:ny };
	};
	
	const hitTestPoint = ( element, bbox, x, y ) => 
	{
		const bitmapCache = element.data().bitmapCache;

		if( bitmapCache )
			return bitmapCache.getContext( '2d' ).getImageData( x - bbox.x, y - bbox.y, 1, 1 ).data[ 3 ] > 0;
		else 
			return false;
	};
	
	const getElementFromPoint = ( x, y, hitTest ) => 
	{
		const objects = _objects.selectAll( 'g[class]' );
		
		if( x < 0 || x > width || y < 0 || y > height )
			return null;
		
		let element;
		let bbox;
		
		if( _logo )
		{
			element = _logo;
			bbox = element.getBBox();
			
			if( !hitTest && Snap.path.isPointInsideBBox( bbox, x, y ) ) 
				return element;

			if( hitTest && hitTestPoint( element, bbox, x, y ) )
				return element;
		}

		for( let i = objects.items.length - 1; i >= 0; i-- )
		{	
			element = objects.items[ i ];
			
			if( element.attr( 'visibility' ) == 'hidden' )
				continue;
				
			bbox = element.getBBox();
			
			if( !hitTest && Snap.path.isPointInsideBBox( bbox, x, y ) ) 
				return element;

			if( hitTest && hitTestPoint( element, bbox, x, y ) )
				return element;
		}
		
		return null;
	};
	
	const getHandleFromPoint = ( x, y ) => 
	{
		const handles = _handles.selectAll( '*' );
		
		let handle;
		let bbox;
		
		for( let i = handles.items.length - 1; i >= 0; i-- )
		{
			handle = handles.items[ i ];
			
			if( handle.attr( 'visibility' ) == 'hidden' )
				continue;
				
			bbox = handle.getBBox();
			
			if( Snap.path.isPointInsideBBox( bbox, x, y ) ) 
				return handle;
		}
		
		return null;
	};
	
	const setPointerDown = ( value ) => 
	{
		if( _placementPointSelectionMode ) 
			return;
			
		const lastAction = _action;
		const lastSelectedElement = _selectedElement;
		
		_action = null;
		_pointerIsDown = value;

		// mousedown
		if( _pointerIsDown )
		{
			_pointerDownX = _pointerX;
			_pointerDownY = _pointerY;
			
			if( _selectedElement )
			{
				const hoverHandle = getHandleFromPoint( _pointerDownX, _pointerDownY );

				if( hoverHandle ) 
				{
					_action = hoverHandle.data().action;

					if( _action != null )
					{
						_actionMatrix = _selectedElement.transform().localMatrix;
						_actionScale = _actionMatrix.split().scalex;
						_actionPivotX = _selectedElement.getBBox().cx;
						_actionPivotY = _selectedElement.getBBox().cy;
						
						if( _action == 'rotate' )
							_actionAngle = Math.atan2( _pointerX - _actionPivotX, _pointerY - _actionPivotY ) / Math.PI * 180;
						
						if( _action == 'scale' )
							_actionDistance = getDistance( _pointerX, _pointerY, _actionPivotX, _actionPivotY );
					}
				}									
			}
			
			const topBBoxElement = getElementFromPoint( _pointerDownX, _pointerDownY, false );
			const topPixelElement = getElementFromPoint( _pointerDownX, _pointerDownY, true );
		
			if(  _action == null && _selectedElement && 
				( ( topPixelElement == _selectedElement ) || 
				  ( topPixelElement == null &&  topBBoxElement == _selectedElement ) ) )
			{
				_actionMatrix = _selectedElement.transform().localMatrix;
				_action = 'drag';
				
				// console.log( 're-drag-start' );
			}

			if( _action == null && ( _selectedElement != topPixelElement || _selectedElement == null ) )
			{						
				if( topPixelElement || topBBoxElement )
				{	
					selectElement( ( topPixelElement || topBBoxElement ).data().uuid );									
					
					// console.log( 'select' );
					
					_actionMatrix = _selectedElement.transform().localMatrix;
					_action = 'drag';
					
					if( _selectedElement != _logo )
						_objects.add( _selectedElement );

					// console.log( 'drag-start' );
				}
				else if( !_pointerIsOnSurface ) // not on surface
				{
					console.log( 'mousedown deselect' );
					removeSelection(); 
				}
			}
		}
		
		
		// mouseup
		if( !_pointerIsDown && _selectedElement && 
			_pointerDownX > 0 && _pointerX > 0 && 
			_pointerDownX == _pointerX && _pointerDownY == _pointerY )
		{
			if( !Snap.path.isPointInsideBBox( _selectedElement.getBBox(), _pointerX, _pointerY ) && getHandleFromPoint( _pointerX, _pointerY ) == null )
			{
				console.log( 'mouseup deselect' );
				removeSelection();
			}

			if( lastAction == 'delete' )
				removeElement( lastSelectedElement.data().uuid );
				
		}

		if( lastSelectedElement && ( lastAction == 'rotate' || lastAction == 'scale' ) )
			updateElementImage( lastSelectedElement ); 
			
		// console.log( 'action:', _action );
		
		if( _pointerIsDown && _action != null ) 
			_signals.actionStarted.dispatch();
		else 
			_signals.actionFinished.dispatch();
	};
	
	const setPointer = ( x, y ) => 
	{
		_pointerX = x;
		_pointerY = y;
		_pointerIsOnSurface = x >= 0 && x <= width && y >= 0 && y <= height;
		
		if( !_hoverEnabled || _placementPointSelectionMode ) 
			return;
			
		const topBBoxElement = getElementFromPoint( _pointerX, _pointerY, false );
		const topPixelElement = getElementFromPoint( _pointerX, _pointerY, true );
		const hoverElement = topPixelElement || topBBoxElement;
		
		if( hoverElement )
		{
			if( hoverElement != _hoverElement )
			{
				_hoverElement = hoverElement;
				
				if( !_selectedElement )
					drawHoverBounds( _hoverElement );									
			}
		}
		else if( _hoverElement != null )
		{
			_hoverElement = null;
			
			clearHoverBounds();	
		}
		
		if( _action == 'drag' && _pointerIsOnSurface )
		{		
			_selectedElement.transform
			( 
				new Snap.Matrix() 
				.translate( _pointerX - _pointerDownX, _pointerY - _pointerDownY )
				.add( _actionMatrix )
			); 

			drawSelectedBounds( _selectedElement );									
			updateHandles( _selectedElement );
		}
		
		if( _action == 'rotate' )
		{	
			_selectedElement.transform
			(
				new Snap.Matrix()
					.rotate( _actionAngle - ( Math.atan2( _pointerX - _actionPivotX, _pointerY - _actionPivotY ) / Math.PI * 180 ), _actionPivotX, _actionPivotY )
					.add( _actionMatrix )
			); 
			
			drawSelectedBounds( _selectedElement );
			updateHandles( _selectedElement );
		}
		
		if( _action == 'scale' )
		{
			let scale = getDistance( _pointerX, _pointerY, _actionPivotX, _actionPivotY ) / _actionDistance;
			
			_selectedElement.transform
			( 
				new Snap.Matrix()
					.scale( scale, scale, _actionPivotX, _actionPivotY )
					.add( _actionMatrix )
			);
			
			drawSelectedBounds( _selectedElement );
			updateHandles( _selectedElement );
			
			_signals.elementScaleChanged.dispatch( _selectedElement.data().uuid, _selectedElement.transform().localMatrix.split().scalex );
		}
	};
	
	const addTextToClientXY = ( text, clientX, clientY, attributes ) => 
	{
		const rect = _paper.node.getBoundingClientRect();
		
		if( clientX < rect.left || clientX > rect.right || clientY < rect.top || clientY > rect.bottom )
		{
			console.log( 'Outside the surface' );
			return null;
		}
		
		const uuid = addText( text, ( clientX - rect.left ) / _scale, ( clientY - rect.top ) / _scale, attributes );
		
		selectElement( uuid );
		
		return uuid;
	};
	
	const toJSON = () => 
	{
		const json = 
		{ 
			objects:[] 
		};
		
		_objects.selectAll( 'g[class]' ).forEach( element => 
		{
			let object = {};
				object.type = element.data().type;
				object.transform = element.transform().toString();
				object.opacity = element.attr( 'opacity' );
				
			if( element.data().type == 'text' )
			{
				object.text = element.data().text.join( '\n' );
				object.textAnchor = element.data().textAnchor;
				object.fontStyle = element.data().fontStyle;
				object.fontWeight = element.data().fontWeight;
				object.fontFamily = element.data().fontFamily;
				object.fillType = element.data().fillType;
				object.fillColor = element.data().solid;
				object.fillStopColor1 = element.data().stopColor1;
				object.fillStopColor2 = element.data().stopColor2;
				object.stroke = element.data().stroke;
				object.strokeWidth = element.data().strokeWidth;
				object.shadowEnabled = element.data().shadowEnabled;
			}
			
			json.objects.push( object );
			
		} );
		
		return json;
	};
	
	const disposeMultilineText = ( element ) => 
	{
		const filledTSpans = element.data().filledTSpans;
		const strokedTSpans = element.data().strokedTSpans;
		
		if( filledTSpans )
		{
			for( let i = filledTSpans.length - 1; i--; i >= 0 )
				filledTSpans[ i ].remove();	
		}
		
		if( strokedTSpans )
		{
			for( let i = strokedTSpans.length - 1; i--; i >= 0 )
				strokedTSpans[ i ].remove();	
		}
		
		if( element.data().filledText )
			element.data().filledText.remove();
		
		if( element.data().strokedText )
			element.data().strokedText.remove();
		
		element.data().strokedTSpans = null;
		element.data().strokedText = null;
		element.data().filledTSpans = null;
		element.data().filledText = null;
	};
	
	const updateMultilineText = ( element, text ) =>
	{
		if( !Array.isArray( text ) )
		{
			if( text.indexOf( '\n' ) == -1 )
				text = [ text ];
			else
				text = condenseNewlines( text ).split( '\n' );
		}
		
		const oldText = Array.isArray( element.data().text ) ? element.data().text.join( '\n' ) : '';
		const newText = text.join( '\n' );
		const textChanged = oldText != newText;

		if( textChanged )
			disposeMultilineText( element );
		
		let	filledTextAttributes = {};
			filledTextAttributes.fill = 'none';
			filledTextAttributes.alignmentBaseline = 'centeral';
			filledTextAttributes.fontSize = 48;

		let strokedTextAttributes = Object.assign( {}, filledTextAttributes );
			strokedTextAttributes.stroke = element.data().stroke;
			strokedTextAttributes.strokeWidth = element.data().strokeWidth;
			strokedTextAttributes[ 'stroke-linecap' ] = 'square';
			strokedTextAttributes[ 'stroke-linejoin' ] = 'miter';
			strokedTextAttributes[ 'stroke-miterlimit' ] = 2;
			
		if( element.data().fillType == 'solid' )
			filledTextAttributes.fill = element.data().solid;
		else if( element.data().fillType == 'gradient' )
			filledTextAttributes.fill = element.data().gradient;
				
		const textAnchor = element.data().textAnchor;
		const strokedText = textChanged ? element.g() : element.data().filledText;
		const filledText = textChanged ? element.g() : element.data().strokedText;
		const filledTSpans = textChanged ? [] : element.data().filledTSpans;
		const strokedTSpans = textChanged ? [] : element.data().strokedTSpans;
			
		if( textChanged )
		{
			text.forEach( ( line, index ) => 
			{
				filledTSpan = filledText.text( 0, 0, line );
				filledTSpan.node.setAttributeNS( 'http://www.w3.org/XML/1998/namespace', 'xml:space', 'preserve' );
				
				strokedTSpan = strokedText.text( 0, 0, line );
				strokedTSpan.node.setAttributeNS( 'http://www.w3.org/XML/1998/namespace', 'xml:space', 'preserve' );
				
				filledTSpans.push( filledTSpan );
				strokedTSpans.push( strokedTSpan );
			} );
		}
		
		if( !hasFont( element.data().fontFamily, element.data().fontStyle, element.data().fontWeight ) )
			addFont( element.data().fontFamily, element.data().fontStyle, element.data().fontWeight );

		for( let i = 0; i < filledTSpans.length; i++ )
		{	
			filledTSpans[ i ].attr( filledTextAttributes );
			filledTSpans[ i ].attr
			( { 
				x:0, 
				dy:i > 0 ? i + 'em' : 0, 
				fontStyle:element.data().fontStyle,
				fontWeight:element.data().fontWeight,
				fontFamily:'"' + element.data().fontFamily + '"',
				textAnchor:textAnchor,
			} );
			
			strokedTSpans[ i ].attr( strokedTextAttributes );
			strokedTSpans[ i ].attr
			( { 
				x:0, 
				dy:i > 0 ? i + 'em' : 0, 
				fontStyle:element.data().fontStyle,
				fontWeight:element.data().fontWeight,
				fontFamily:'"' + element.data().fontFamily + '"',
				textAnchor:textAnchor,
			} );
		}

		
		const bbox = filledText.getBBox( true );
		const x = textAnchor == 'start' ? ( bbox.width / -2 ) : ( textAnchor == 'end' ? ( bbox.width / 2 ) : 0 );

		for( let i = 0; i < filledTSpans.length; i++ )
		{
			filledTSpans[ i ].attr( { x:x } );
			strokedTSpans[ i ].attr( { x:x } );
		}
		
		if( textChanged )
		{
			element.data().filledText = filledText;
			element.data().strokedText = strokedText;
			element.data().filledTSpans = filledTSpans;
			element.data().strokedTSpans = strokedTSpans;
			element.data().text = text;
		}
		
		updateElementImage( element );
	};
	
	const fromJSON = ( json ) => 
	{
		if( json && Array.isArray( json.objects ) )
		{
			json.objects.forEach( object => 
			{
				let attributes = {};
					attributes.transform = object.transform;
					attributes.filter = object.shadowEnabled ? _shadow : null;
					attributes.opacity = object.opacity || 1;

				let uuid = generateUUID( 'T' );
				let element = _objects.g().attr( { class:'text' } );
					element.data().type = 'text';
					element.data().textAnchor = isValidTextAnchor( object.textAnchor ) ? object.textAnchor : 'middle';
					element.data().uuid = uuid;
					element.data().fillType = object.fillType || 'solid';
					element.data().solid = object.fillColor || '#FFFF00';
					element.data().stopColor1 = object.fillStopColor1 || '#FF0000';
					element.data().stopColor2 = object.fillStopColor2 || '#FFFF00';
					element.data().gradient = _paper.gradient( 'l(0,0,0,1)' + element.data().stopColor1 + '-' + element.data().stopColor2 );
					element.data().shadowEnabled = object.shadowEnabled;
					element.data().fontFamily = object.fontFamily;
					element.data().fontStyle = object.fontStyle || 'normal';
					element.data().fontWeight = object.fontWeight || '400';
					element.data().stroke = Snap.getRGB( object.stroke ).hex;
					element.data().strokeWidth = isNaN( parseInt( object.strokeWidth ) ) ? 1 : parseInt( object.strokeWidth )
					
					if( !hasFont( element.data().fontFamily, element.data().fontStyle, element.data().fontWeight ) )
						addFont( element.data().fontFamily, element.data().fontStyle, element.data().fontWeight );
					
					element.attr( attributes );	

				updateMultilineText( element, object.text );

				_signals.elementAdded.dispatch( uuid );
				
			} );
		}
	};
	
	const updatePaperImage = ( drawMode, onComplete ) => 
	{
		// console.log( 'updatePaperImage' );
		
		const selectedElement = _selectedElement;
		const zoom = _scale;
		
		if( drawMode == DRAW_MODE_FRAME || drawMode == DRAW_MODE_RESULT )
			_background.attr( { visibility:'hidden' } );

		if( drawMode == DRAW_MODE_FRAME )
			_objects.attr( { visibility:'hidden' } );

		if( drawMode == DRAW_MODE_PREVIEW_WITHOUT_LOGO )
			_logoLayer.attr( { visibility:'hidden' } );
		
		_patternBg.attr( { visibility:'hidden' } );
		_hoverBounds.attr( { visibility:'hidden' } );
		_selectedBounds.attr( { visibility:'hidden' } );
		_handles.attr( { visibility:'hidden' } ); 
		_maskBounds.attr( { visibility:'hidden' } );
		
		_paper.attr( { width:width, height:height } );	
		_root.transform( new Snap.Matrix().scale( 1 ) );
		
																				/*//console.log( _paper.toString() );
																				
																				const image = new Image();
																					  image.src = _paper.toDataURL();
																					  image.onload = () => 
																					  {
																							_textureContext.clearRect( 0, 0, width, height );
																							_textureContext.drawImage( image, 0, 0, image.naturalWidth, image.naturalHeight, 0, 0, width, height );
																							
																							if( onComplete )
																								onComplete();
																					  };
																					  
																				// document.body.appendChild( image );
																				
																				_paper.attr( { width:width * zoom, height:height * zoom } );	
																				_root.transform( new Snap.Matrix().scale( zoom ) );
																					
																				_maskBounds.attr( { visibility:'visible' } );
																				_background.attr( { visibility:'visible' } );
																				_objects.attr( { visibility:'visible' } );
																				_hoverBounds.attr( { visibility:'visible' } );
																				_selectedBounds.attr( { visibility:'visible' } );
																				_handles.attr( { visibility:'visible' } );
																				_logoLayer.attr( { visibility:'visible' } );*/

		_textureContext.clearRect( 0, 0, width, height );
		
		let emptyLength  = _textureCanvas.toDataURL().length,
			prevLength = 0, 
			timerID;

		console.log( 'empty canvas:' + emptyLength );
		
		const onTimer = () => 
		{
			_textureContext.clearRect( 0, 0, width, height );
			_textureContext.drawImage( image, 0, 0, image.naturalWidth, image.naturalHeight, 0, 0, width, height );
			
			let currentLength = _textureCanvas.toDataURL().length;

			if( currentLength > emptyLength && prevLength == currentLength )
			{
				clearInterval( timerID );
				
				console.log( 'filled canvas:' + currentLength );
				if( onComplete )
					onComplete();
			}
			
			if( currentLength > emptyLength )
				prevLength = currentLength;
		};

		const image = new Image();
			  image.src = _paper.toDataURL();
			  
		const ua = navigator.userAgent.toLowerCase(); 
		const isSafari = ua.indexOf( 'safari' ) != -1 && ua.indexOf( 'chrome' ) == -1;
		
		if( isSafari )
		{
			console.log( 'isSafari' );
			timerID = setInterval( onTimer, 500 );
		}
		else 
		{
			console.log( 'isNotSafari' );
			image.onload = () => 
			{
				_textureContext.drawImage( image, 0, 0, image.naturalWidth, image.naturalHeight, 0, 0, width, height );
				
				console.log( 'filled canvas:' + _textureCanvas.toDataURL().length );
				if( onComplete )
					onComplete();
			};
		}

		_paper.attr( { width:width * zoom, height:height * zoom } );	
		_root.transform( new Snap.Matrix().scale( zoom ) );
			
		_maskBounds.attr( { visibility:'visible' } );
		_patternBg.attr( { visibility:'visible' } );
		_background.attr( { visibility:_backgroundEnabled ? 'visible' : 'hidden' } );
		_objects.attr( { visibility:'visible' } );
		_hoverBounds.attr( { visibility:'visible' } );
		_selectedBounds.attr( { visibility:'visible' } );
		_handles.attr( { visibility:'visible' } );
		_logoLayer.attr( { visibility:'visible' } );
	};
	
	const updatePaperSize = () =>
	{
		_paper.attr( { width:width * _scale, height:height * _scale } );	
		
		_root.transform( new Snap.Matrix().scale( _scale ) );
		
		_maskBounds.attr( { strokeWidth:1 / _scale } );
		
		if( _selectedElement )
		{
			drawSelectedBounds( _selectedElement );									
			updateHandles( _selectedElement );
		}
	};
	
	const align = () => 
	{	
		const scrollBarSize = 16;
		const paperRect = _paper.node.getBoundingClientRect();
		const containerRect = domContainer.getBoundingClientRect();
		const containerWidth = containerRect.width - ( containerRect.height < paperRect.height ? scrollBarSize : 0 );
		const containerHeight = containerRect.height - ( containerRect.width < paperRect.width ? scrollBarSize : 0 );
		
		if( containerWidth - paperRect.width > 0 )
		{
			_left = ( containerWidth - paperRect.width ) / 2;
			_paper.node.style.left = _left + 'px';
		}
		else if( _left > 0 )
		{
			_left = 0;
			_paper.node.style.left = _left + 'px';
		}
		
		if( containerHeight - paperRect.height > 0 )
		{
			_top = ( containerHeight - paperRect.height ) / 2;
			_paper.node.style.top = _top + 'px';
		}
		else if( _top > 0 )
		{
			_top = 0;
			_paper.node.style.top = _top + 'px';
		}
		
		// console.log( containerRect, paperRect );
	};
	
	const fitToScreen = () =>
	{
		const scrollBarSize = 16;
		const containerRect = domContainer.getBoundingClientRect();
		const containerWidth = containerRect.width - scrollBarSize;
		const containerHeight = containerRect.height - scrollBarSize;
		const scale = Math.min( containerWidth / width, containerHeight / height );
		
		console.log( scale );
		
		setZoom( scale );
	};
	
	const setZoom = ( zoom ) => 
	{
		if( isNaN( zoom ) )
			return;
			
		if( zoom < 0.1 )
			zoom = 0.1;
			
		if( zoom > 10 )
			zoom = 10;
			
		if( zoom == _scale )
			return;
			
		// console.log( 'setZoom -> ' + zoom );
			
		_scale = zoom;
		
		updatePaperSize();	
		
		align();
		
		_signals.zoomChanged.dispatch( _scale );
	};
	
	 // Для корректного рендера, SVG должен быть добавлен к DOM и быть видимым
	domContainer.appendChild( _paper.node );
	
	updatePaperSize();	
	
	align();
	
	addLogo();
	
	if( importedData )
		fromJSON( importedData );
	

	window.addEventListener( 'resize', event => align() );
	
	window.addEventListener( 'mousemove', event => 
	{
		const rect = _paper.node.getBoundingClientRect();
		
		setPointer( ( event.clientX - rect.left ) / _scale, ( event.clientY - rect.top ) / _scale );						
	} );
	
	document.addEventListener( 'mousedown', event => 
	{
		if( event.target.closest( domContainerID ) )
		{
			const rect = _paper.node.getBoundingClientRect();
			
			setPointer( ( event.clientX - rect.left ) / _scale, ( event.clientY - rect.top ) / _scale );
			setPointerDown( true );
		}
	} );
	
	window.addEventListener( 'mouseup', event => 
	{
		const rect = _paper.node.getBoundingClientRect();
		
		setPointer( ( event.clientX - rect.left ) / _scale, ( event.clientY - rect.top ) / _scale );
		setPointerDown( false );
	} );

	
	// =====================================================

	return {
		signals:_signals, 
		getFrameDataURL:( onComplete ) => 
		{
			updatePaperImage( DRAW_MODE_FRAME, () =>
			{
				if( onComplete )
					onComplete( _textureCanvas.toDataURL() );
			} );
		},
		getPreviewDataURL:( onComplete ) => 
		{
			updatePaperImage( DRAW_MODE_PREVIEW, () =>
			{
				if( onComplete )
					onComplete( _textureCanvas.toDataURL() );
			} );
		},
		
		getPreview2DataURL:( onComplete ) => 
		{
			updatePaperImage( DRAW_MODE_PREVIEW_WITHOUT_LOGO, () =>
			{
				if( onComplete )
					onComplete( _textureCanvas.toDataURL() );
			} );
		},
		
		getResultDataURL:( onComplete ) =>
		{
			updatePaperImage( DRAW_MODE_RESULT, () =>
			{
				if( onComplete )
					onComplete( _textureCanvas.toDataURL() );
			} );
		},
		toJSON,
		setPlacementPointSelectionMode,
		getElementScale,
		setElementScale,
		getElementAttributes,
		setElementAttributes:( uuid, attributes ) =>
		{
			if( setElementAttributes( uuid, attributes ) )
				updateElementImage( getElementByUUID( uuid ) );
		},
		removeElement,
		addTextToClientXY,
		setLogoInverted,
		getLogoInverted:() => _logoInverted,
		fitToScreen,
		setZoom,
		getZoom:() => _scale,
		getBackgroundEnabled:() => _backgroundEnabled,
		setBackgroundEnabled:( value ) => 
		{
			if( _backgroundEnabled != value )
			{
				_backgroundEnabled = value;
				_background.attr( { visibility:_backgroundEnabled ? 'visible' : 'hidden' } );
			}
		},
	};
};
	