import React, { useState } from 'react';
import TableHeader from "./TableHeader";
import TableRow from "./TableRow";

function Table( props ) {
    const preHeaders = [];
    const preRows = [];

    preHeaders.push( <TableHeader content='ID' key={ 'id' } /> );

    props.headers.forEach( ( element, index ) => {
        preHeaders.push( <TableHeader content={ element } key={ index } /> );
    } );

    props.rows.forEach( ( element, index ) => {
        preRows.push( <TableRow columns={ element } key={ index } /> );
    } );

    const [ rows, setRows ] = useState( preRows );
    const [ headers, setHeaders ] = useState( preHeaders );

    return (
        <div>
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
        </div>
    );
}

export default Table;