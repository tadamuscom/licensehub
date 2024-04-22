import React, {useState} from "react";
import { columnAddLoader, getElementID, resetTable, triggerColumnError, Header, Table, LinkButton, HeadingTwo } from "@tadamus/wpui";
import NewProduct from "./components/NewProduct";
import sanitizeHtml from "sanitize-html";

function App( props ) {
    const [ loading, setLoading ] = useState( false );

    const newOnClick = ( event ) => {
        event.preventDefault();

        const newProduct = document.getElementById( 'tada-new-product' );

        event.target.style.display = 'none';
        newProduct.style.display = 'inherit';
    }

    const tableOnBlur = ( event ) => {
        const table = event.currentTarget.parentNode.parentNode.parentNode.parentNode;
        const row = event.currentTarget.parentNode.parentNode;
        const columnElement = event.currentTarget.parentNode
        const column = sanitizeHtml( columnElement.getAttribute( 'column' ) );
        const value = sanitizeHtml( event.currentTarget.innerHTML );
        const id = getElementID( row );

        resetTable( columnElement );
        setLoading( true );

        if( ! column || column.length < 1 ){
            triggerColumnError( columnElement, table, 'There has been an error, the column name could not be identified' );
            setLoading( false );

            return;
        }

        if( column === 'status' ){
            const acceptedStatuses = [ 'active', 'inactive' ];

            if( ! acceptedStatuses.includes( value ) ){
                triggerColumnError( columnElement, table , 'Status can only be set to \'active\' or \'inactive\'' );
                setLoading( false );

                return;
            }
        }

        const loader = columnAddLoader( row );

        const data = {
            nonce: lchb_products.nonce,
            id: id,
            column: column,
            value: value
        };

        const beforeUnloadHandler = (event) => {
            event.preventDefault();

            event.returnValue = true;
        };

        window.addEventListener("beforeunload", beforeUnloadHandler);

        wp.apiFetch( {
            path: '/tadamus/lchb/v1/update-product',
            method: 'PUT',
            data: data
        } ).then( ( result ) => {
            if( ! result.success ){
                loader.remove();

                triggerColumnError( columnElement, table, result.data.message );
            }else{
                loader.remove();
            }

            window.removeEventListener("beforeunload", beforeUnloadHandler);
        }).catch( ( result ) => {
            loader.remove();

            triggerColumnError( columnElement, table, result.message );

            window.removeEventListener("beforeunload", beforeUnloadHandler);
        } );
    }

    return (
        <div>
            <Header pageTitle='Products' />
            <LinkButton
                click={ newOnClick }
                label='Add Product'
            />
            <NewProduct />
            <HeadingTwo label='Products' />
            <Table
                headers={ lchb_products.fields }
                rows={ lchb_products.products }
                editable={ true }
                onBlur={ tableOnBlur }
            />
        </div>
    );
}

export default App;