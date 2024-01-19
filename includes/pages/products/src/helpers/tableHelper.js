import sanitizeHtml from "sanitize-html";

export const resetTable = ( columnElement ) => {
    const errorElement = document.getElementById( 'table-error' );

    if( columnElement.style.borderColor === 'red' ) {
        columnElement.style.borderColor = '#000';
    }

    if( errorElement ){
        errorElement.remove();
    }
}

export const triggerColumnError = ( columnElement, table , message ) => {
    columnElement.style.borderColor = 'red';

    const tableParent = table.parentNode;
    const errorParagraph = document.createElement( 'p' );
    errorParagraph.id = 'table-error';
    errorParagraph.innerText = message;
    errorParagraph.style.color = 'red';

    tableParent.appendChild( errorParagraph );
}

export const getElementID = ( row ) => {
    let returnable;

    row.childNodes.forEach( ( element, index ) => {
        switch( element.getAttribute( 'column' ) ){
            case 'id':
            case 'ID':
                returnable = element.innerText;
        }
    } );

    return sanitizeHtml( returnable );
}

export const columnAddLoader = ( row ) => {
    const children = row.childNodes;
    let existingLoader = false;

    children.forEach( element => {
        if( element.classList.contains( 'tada-loader' ) ){
            existingLoader = element;
        }
    } );

    if( existingLoader === false ){
        const loader = document.createElement( 'div' );
        loader.classList.add( 'tada-loader' );

        row.appendChild(loader);

        return loader;
    }

    return existingLoader;
}