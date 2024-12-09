const TextureFactory = ( { domContainerID, width, height, source, mask, orientation, quantity } ) =>
{
	const domContainer = document.querySelector( domContainerID );
	
	const ZONE_COLORS = [ 0xFF9900, 0x99FF00, 0x0099FF, 0xFF0099 ];
	const ZONE_H_WIDTH = 1200;
	const ZONE_V_WIDTH = 1200;
	const ZONE_V_HEIGHT = 1800;
	
	const HANDLE_SIZE = 24;
	const HANDLE_ROTATE_URL = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAtJJREFUeNrUWk1W2zAQltPucV/Z2zz2DZQDxIWu8x6wLyk3gBMUOEALXdMAF4AbNJwgMRdIcoIkJzD5DAYhS7Ilj0I8783Gia3v0/xoZmzGXqUz1/5ckyXX/jPWN9KtAXBRu/zOJzXV1BIvbhNFUTIcDpNlFWADRsGdXhm5AH/y6yQZ9AekJHjMDT4QwjBkLmRn+zuLBzHJs0SMDbYAmU6npCQWQuC+d5/qeDx2TuLFn6rI3e1dcvjzMFlfW08+eB+Vit8nk0mltYRMZE8AQBCknz+takGL+nVzqxIJEgK9/73C3dbpn9/n70fg+OjYGjgUrvZuLoTFy7gI/gf3Eq101b2ufBZYE9DtPIDCLUTf3v62QwremgCyjAo8iKmCMiNABd6KAMDJMg2uFQEDAUrwVgRUrgOrFNcuI/L6yogAdl8GHgHqUnTPNyKAwJQFrCvBhiGL6SxsRIDPItTZRAded14YEZAFrivhwessrewHZBWlKK2o5azsns0rVl5GozF9Od1sfnFGoLmxkbtWVH5rCQwkNwdB6I6AZHOmglWMCMxm+Zt9f4Utk2gJtFp5f4/j2BmYrHtz2lLaLFJWZC5bJmko06jsFHZ1iJmsVTqN+r4/zwzNXGqTpdeqcn11UyorGbtQJDHh6ekZOYG/Fxe5a+12u/pUAtWkbSVqUriZnPjG5bSsjcQCFCNDVaNEVo3qrFCVBCYbqkZJN3axaillZs4Ws3EnWZle1j2tm/q93X3loii7saNFglJcN09C92fSkXkZC/bEoPKQNgyD9PAJgiA9yXEPTu84fkjTr662Oej8YJf/Lguzjud59rNR+KbOEosYdpGMFlUxYao2MUQ23EV2KjOp0+26zZCXjABPBMEntoSqsaNsgmdLwCiIy76NQZBnwcuX5VTtKB/E5AQWITyBBqu5NN6WyqOlByzDWPsX3bX/1KD2H3vU+nObRwEGAJLv5YOtgiqoAAAAAElFTkSuQmCC';
	const HANDLE_DELETE_URL = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAjlJREFUeNrsWoGNwjAMTNEPABswAhsAG8AGZYMyAWICRqiYADYoGxQmgBG6QT8XkWKi55+kTkn0tRRRUhR8sR2f3QrxkFSOUo468FHedX2SPALFzZHTna8jHcoSjdvMZrP6er3WoQp0g46GOz0Qhaw8BUF1Tu4XSuR9EYMkSdJcD0Tk8uVj0fP5LKqqepqbTCZiOBx6AdH4U1s5HA61VPLlqZFlGUscGOvyADCD69XYbDasANhi4Hg8qk+4ibSEKIriaSwWC3V/v9+HGQPa5+HrWlkqp9NJgbzdbp+LAfiwz8w6Ho/rsiz9xECe553QA2RaGwBvu5A2PVxkt9uxuwFcbLvdqk+vMYAglbvk5TwHAFv5f5kYJqZc5NMysHGdLsT2f6zYKHZ/Pp+rawQyAppDlsulyiNpmgqZqYU8Tt9mo9ZUQv8WvIdLbNdsRSW0icE4qWVGo5FYr9e/zuEoxhx2nMtNrQH85DZQFi5ggjLnAABzmjd1GsShyscBmIVPdACoi/11+rACuFwu7GA6ATCdTllM3wdxD4CxDmjDtZwBcNe2rrzKORP7KM47AdAVre6D2GdhQu+1TYitGltIZlAmyzL1nTa0UJzgPm0AIH5QsNCA1QlRJ0ivjS2zB1oURetiRoKx7pk69YUoX8GuY+dWq5UTf6G7r8lcm3Wsu9PcXTpYwbU77fyICRnUtov2yqKIF6tOBCnq+2dkQeWBUOiBLQeL/kF3KiJ/1QAS9cseTfYXEb5u8y3AAODTUeBi9yhEAAAAAElFTkSuQmCC';
	const HANDLE_SCALE_URL = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAb9JREFUeNrsmo2xRDAQx9ebV4ASlKAEOqADnaADHShFCXRAB3TgZTPH5ZzLSC5i88bO7Iw5w/3+sh9JAHhaxrxlPhP39sH6YrUD4FuvxSc/O+p8JNawiaJo7vt+pmrIhoybcHoqogwvihCZvccBN3YeXDDP89bjH3Dcfm3+2TRNUFWV0jVJkkAYhjQEdF0HZVkqXycTcIeQigVBAKwMfgwvHCHyApqm2YWP41jrnpeH0AKv8/QvF7AH7/s+HynyAj7BY4iRFyCDl5VMEgJMwlsXYBreqoAz4K0JOAt+nUUvfpbVdf0yh2fwc9u20jk/E8d9HMe385tV2fkCEII96UPwR8y6gEVEnudGVn33ioySGRUwDAMURcGrjk0zkgOYmJigeB9M2L3qYcoOJzFCLOVMlnwi/OJYOi8XgFVD3PQ6Cm9zBL7KAeys2GHFmMfOih0WOy3pJKYAry2ACryWAErwygKowSsJwCZFDV5pXwgFbLf7robXTmIq8F/tzKVpuvs768BK2yKXCJDtomGo2RTwv3en8eWCTn5YXdzcKzJKK7Jtradoe4zOv+jOwPFPDXj/AYc/9lgsAwc/t/kTYACWIYc9GL1xNwAAAABJRU5ErkJggg==';

	
	const Signal = signals.Signal;
	
	const _signals =
	{
		zoneAdded:new Signal(),
		zoneRemoved:new Signal(),
		zoneSelected:new Signal(),
		zoneDeselected:new Signal(),
		actionStarted:new Signal(),
		actionFinished:new Signal(),
		zoomChanged:new Signal,
	};
	
	const getValidOrientation = ( value ) =>
	{
		value = value.toLowerCase();
		
		if( value == 'vertical' || value == 'square' )
			return value;
		
		return 'vertical';
	};
	
	const getValidQty = ( quantity ) => 
	{
		if( isNaN( quantity ) ) return 1;

		return Math.max( Math.min( Math.floor( quantity ), 4 ), 1 );
	};
	
	let _scale = 1.0;
	let _left = 0;
	let _top = 0;

	const _paper = Snap( width, height );
		  _paper.clear();
		  _paper.attr( { 'xmlns:xlink':'http://www.w3.org/1999/xlink' } );
		  _paper.append( Snap.parse( '<pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse"><rect fill="black" x="0" y="0" width="10" height="10" opacity="0.1"/><rect fill="white" x="10" y="0" width="10" height="10"/><rect fill="black" x="10" y="10" width="10" height="10" opacity="0.1"/><rect fill="white" x="0" y="10" width="10" height="10"/></pattern>' ) );
	
	const _pattern 			= _paper.select( 'pattern' ).toDefs();
	const _root				= _paper.g().transform( 's' + _scale );
	const _background 		= _root.rect( 0, 0, width, height ).attr( { fill:_pattern, id:'background' } );	
	const _editor 			= _root.g().attr( { id:'editor' } );
	const _objects 			= _editor.g().attr( { id:'objects' } );
	const _frame 			= _root.image( source.toDataURL(), 0, 0, width, height ).attr( { id:'frame' } );
	const _hoverBounds 		= _root.g().attr( { id:'hover-bounds' } );
	const _selectedBounds 	= _root.g().attr( { id:'selected-bounds' } );
	const _handles 			= _root.g().attr( { id:'handles' } );
	
				
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
		_deleteHandle;
		
	let _orientation = getValidOrientation( orientation );
	let _maxZonesQty = getValidQty( quantity );
	let _zonesQty = 0;

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

		if( element.data().type == 'zone' )
		{
			if( element.data().bitmapCache == null ) 
				element.data().bitmapCache = document.createElement( 'canvas' );
				
			const bbox = element.getBBox();
			const canvas = element.data().bitmapCache;
				  canvas.width = bbox.width;
				  canvas.height = bbox.height;
				  
			/* // BEGIN_TEMP
			if( canvas.parentElement == null ) 
				domContainer.appendChild( canvas );
			// END_TEMP */

			const context = canvas.getContext( '2d' );
				  context.clearRect( 0, 0, bbox.width, bbox.height );
			
			const matrix = element.transform().localMatrix;
			const rect = element.select( 'rect' );
			
			context.translate( -bbox.x, -bbox.y );
			context.transform( matrix.a, matrix.b, matrix.c, matrix.d, matrix.e, matrix.f );
			context.fillStyle = 'black';
			context.fillRect
			( 
				0, 
				0,
				parseInt( rect.attr( 'width' ) ), 
				parseInt( rect.attr( 'height' ) ) 
			);
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

		
		_deleteHandle = _handles.image( HANDLE_DELETE_URL, 0, 0, HANDLE_SIZE, HANDLE_SIZE );
		_deleteHandle.data().action = 'delete';

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
	
	const removeSelection = () => 
	{
		const uuid = _selectedElement ? _selectedElement.data().uuid : null;
		
		_selectedElement = null;
		
		if( uuid )
		{						
			clearHoverBounds();
			clearSelectedBounds();									
			removeHandles();
			
			_frame.attr( { opacity:1.0 } );
			_signals.zoneDeselected.dispatch( uuid );

			return true;
		}
		
		return false;
	};
	
	const selectZone = ( uuid ) => 
	{
		const element = getElementByUUID( uuid );
		
		if( element )
		{
			removeSelection();
			
			_selectedElement = element;

			clearHoverBounds();
			drawSelectedBounds( _selectedElement );
			addHandles( _selectedElement );
			
			_frame.attr( { opacity:0.75 } );
			_signals.zoneSelected.dispatch( uuid );

			return true;
		}
		
		return false;
	};

	const removeZone = ( uuid ) => 
	{
		const element = getElementByUUID( uuid );
		
		if( element )
		{
			if( _selectedElement == element )
				removeSelection();

			element.data().source = null;
			element.data().bitmapCache = null;
			element.remove();
			
			_zonesQty--;
			_signals.zoneRemoved.dispatch( uuid );
			
			return true; 
		}
		
		return false;
	};

	const getDistance = ( x1, y1, x2, y2 ) => 
	{
		const a = x1 - x2;
		const b = y1 - y2;

		return Math.sqrt( a * a + b * b );
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
		
			if
			(  
				_action == null && 
				_selectedElement && 
				( 
					( topPixelElement == _selectedElement ) || 
					( topPixelElement == null &&  topBBoxElement == _selectedElement ) 
				) 
			)
			{
				_actionMatrix = _selectedElement.transform().localMatrix;
				_action = 'drag';
			}

			if( _action == null && ( _selectedElement != topPixelElement || _selectedElement == null ) )
			{						
				if( topPixelElement || topBBoxElement )
				{	
					selectZone( ( topPixelElement || topBBoxElement ).data().uuid );									

					_actionMatrix = _selectedElement.transform().localMatrix;
					_action = 'drag';
					
					_objects.add( _selectedElement );
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
			{
				removeZone( lastSelectedElement.data().uuid );
				updateZones();
			}
				
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
		}
	};
	
	const toJSON = () => 
	{
		const json = { objects:[] };
		
		_objects.selectAll( 'g[class=zone]' ).forEach( element => 
		{
			let rect = element.select( 'rect' );
			let matrix = element.transform().localMatrix;
			
			let object = {};
				object.type = element.data().type;
				object.width = parseInt( rect.attr( 'width' ) );
				object.height = parseInt( rect.attr( 'height' ) );
				object.transform = element.transform().toString();
				object.matrix = 'matrix(' + matrix.a + ',' + matrix.b + ',' + matrix.c + ',' + matrix.d + ',' +
								( matrix.e - width / 2 ).toFixed( 2 ) + ',' + ( matrix.f - height / 2 ).toFixed( 2 ) + ')';
			
				console.log( element.transform().localMatrix );
	
			json.objects.push( object );
			
			
		} );
		
		return json;
	};
	
	const updatePaperSize = () =>
	{
		_paper.attr( { width:width * _scale, height:height * _scale } );	
		_root.transform( new Snap.Matrix().scale( _scale ) );
		
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
	
	const getZoom = () => 
	{
		return _scale;
	};
	
	const setZoom = ( zoom ) => 
	{
		if( isNaN( zoom ) ) return;		
		if( zoom < 0.1 ) zoom = 0.1;		
		if( zoom > 10 ) zoom = 10;	
		if( zoom == _scale ) return;
	
		_scale = zoom;
		
		updatePaperSize();	
		
		align();
		
		_signals.zoomChanged.dispatch( _scale );
	};
	
	const updateZones = () =>
	{
		// console.log( 'updateZones' );
		
		const objects = _objects.selectAll( 'g[class=zone]' );

		let w = _orientation == 'vertical' ? ZONE_V_WIDTH : ZONE_H_WIDTH;
		let h = _orientation == 'vertical' ? ZONE_V_HEIGHT : ZONE_H_WIDTH;
			
		for( let i = 0; i < objects.items.length; i++ )
		{	
			let element = objects.items[ i ];
			let rect = element.select( 'rect' );
				rect.attr( { fill:getHex( ZONE_COLORS[ i ] ), width:w, heigth:h } );
			let text = element.select( 'text' );
				text.attr( { text:( i + 1 ).toString() } );
		}
	};

	const setOrientation = ( orientation ) =>
	{
		// console.log( 'setOrintation:' + orientation );
		
		orientation = getValidOrientation( orientation );
		
		if( _orientation != orientation )
		{
			_orientation = orientation;
			
			reset();		
		}
		
	};
	
	const setQty = ( quantity ) =>
	{
		// console.log( 'setQty:' + quantity );
		
		quantity = getValidQty( quantity );
		
		if( _maxZonesQty != quantity )
		{
			_maxZonesQty = quantity;
			
			const objects = _objects.selectAll( 'g[class=zone]' );
			
			let needsUpdate = false;
			
			for( let i = objects.items.length - 1; i >= 0; i-- )
			{	
				if( i >= _maxZonesQty )
				{
					needsUpdate = true;
					removeZone( objects.items[ i ].data().uuid );
				}
				else 
					break;
			}
			
			if( needsUpdate )
				updateZones();
		}
	};
	
	const reset = () => 
	{
		// console.log( 'reset' );
		
		const objects = _objects.selectAll( 'g[class=zone]' );
		
		for( let i = objects.items.length - 1; i >= 0; i-- )
		{	
			removeZone( objects.items[ i ].data().uuid );
		}
	};
	
	const canAddZone = () => 
	{
		return _zonesQty < _maxZonesQty;
	};
	
	const getHex = ( rgb ) =>
	{
		if( rgb < 0 ) rgb = 0;
		if( rgb > 0xFFFFFF ) rgb = 0xFFFFFF;

		rgb = Math.floor( rgb ).toString( 16 );
		
		while( rgb.length < 6 ) rgb = '0' + rgb;
		
		return '#' + rgb;
	};
	
	const addZone = () => 
	{
		if( canAddZone() )
		{
			// console.log( 'addZone' );

			const rectScale = 0.5;
			
			let uuid = generateUUID( 'R' );
			let element = _objects.g().attr( { class:'zone' } );
				element.data().type = 'zone';
				element.data().uuid = uuid;
			
			let w = _orientation == 'vertical' ? ZONE_V_WIDTH : ZONE_H_WIDTH;
			let h = _orientation == 'vertical' ? ZONE_V_HEIGHT : ZONE_H_WIDTH;
			
			let rect = element.rect( -w / 2, -h / 2, w, h );
				rect.attr( { fill:getHex( ZONE_COLORS[ _zonesQty ] ) } );
				
			let text = element.text( 0, 0, ( _zonesQty + 1 ).toString() );
				text.attr
				( {
					textAnchor:'middle',
					fontWeight:'bold',
					alignmentBaseline:'mathematical',
					fontSize:512,
					fill:'#FFFFFF',
				} );
				
			element.transform( new Snap.Matrix().scale( rectScale ).translate( w / 2, h / 2 ) );
			
			updateElementImage( element );
			
			_zonesQty++;
			_signals.zoneAdded.dispatch( uuid );
			
			selectZone( uuid );

			return uuid;
		}
		else 
			console.warn( 'CANNOT_ADD_ZONE' );
	};
	
	
	
	 // Для корректного рендера, SVG должен быть добавлен к DOM и быть видимым
	domContainer.appendChild( _paper.node );
	
	updatePaperSize();	
	align();

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
		toJSON,
		setZoom,
		getZoom,
		fitToScreen,
		canAddZone,
		addZone,
		setOrientation,
		setQty,
		reset,
	};
};
	