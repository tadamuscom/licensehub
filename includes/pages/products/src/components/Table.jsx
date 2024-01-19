import React, { useState } from 'react';
import TableHeader from "./TableHeader";
import TableRow from "./TableRow";

function Table( props ) {
    const preHeaders = [];
    const preRows = [];

    let editable = false;
    let onBlur = false;

    if( props.editable ){
        editable = props.editable;
    }

    if( props.onBlur ){
        onBlur = props.onBlur;
    }

    preHeaders.push( <TableHeader content='ID' key={ 'id' } /> );

    props.headers.forEach( ( element, index ) => {
        preHeaders.push( <TableHeader content={ element } key={ index } /> );
    } );

    props.rows.forEach( ( element, index ) => {
        preRows.push( <TableRow columns={ element } key={ index } editable={ editable } onBlur={ onBlur } /> );
    } );

    const [ rows, setRows ] = useState( preRows );
    const [ headers, setHeaders ] = useState( preHeaders );

    return (
        <table className='tada-table'>
            <thead>
                <tr>
                    { headers }
                </tr>
            </thead>
            <tbody>
                { rows }
            </tbody>
        </table>
    );
}

export default Table;