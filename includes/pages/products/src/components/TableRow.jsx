import React, {useState} from 'react';
import TableColumn from "./TableColumn";

function TableRow(props ) {
    const preColumns = [];

    for ( const [ key, value ] of Object.entries( props.columns ) ) {
        preColumns.push( <TableColumn data={ value } key={ key } column={ key } /> );
    }

    const [ columns, setColumns ] = useState( preColumns );

    const deleteClick = ( e ) => {
        const columns = e.target.parentNode.parentNode.childNodes;

        columns.forEach( ( column, index ) => {
            if( column.getAttribute( 'column' ) === 'id' ){
                const id = column.innerText;
                const loader = document.createElement( 'div' );
                const loaderParent = e.target.parentNode;
                loader.classList.add( 'tada-loader' );

                e.target.remove();
                loaderParent.appendChild( loader );

                wp.apiFetch( {
                    path: '/tadamus/lchb/v1/delete-product',
                    method: 'POST',
                    data: {
                        nonce: lchb_products.nonce,
                        id: id
                    }
                } ).then( ( result ) => {
                    if( result.success ){
                        loader.remove();
                        loaderParent.innerText = '✅';

                        location.reload();
                    }else{
                        console.log( result.message )
                    }
                } );
            }
        } );
    }

    return (
        <tr>
            { columns }
            <td><button className='tada-action-icon tada-delete-btn' onClick={ deleteClick }>Delete</button></td>
        </tr>
    );
}

export default TableRow;