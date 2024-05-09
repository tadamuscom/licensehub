import { TableHeader } from '@global/components/table/TableHeader';
import { TableRow } from '@global/components/table/TableRow';

/**
 * Add a Table
 *
 * @since 1.0.2
 *
 * @param editable
 * @param onBlur
 * @param className
 * @param onDelete
 * @param headers
 * @param rows
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
export const Table = ({
	editable,
	onBlur,
	className,
	onDelete,
	headers,
	rows,
	...props
}) => {
	const defaultClasses = 'tada-table mt-3';

	return (
		<table
			className={className ? defaultClasses + ' ' + className : defaultClasses}>
			<thead>
				<tr>
					<TableHeader content="ID" key={'id'} />
					{headers.map((header, index) => (
						<TableHeader content={header} key={index} />
					))}
				</tr>
			</thead>
			<tbody>
				{rows.map((row, index) => (
					<TableRow
						columns={row}
						key={index}
						editable={editable ? editable : false}
						onBlur={onBlur ? onBlur : false}
						onDelete={onDelete}
						{...props}
					/>
				))}
			</tbody>
		</table>
	);
};
