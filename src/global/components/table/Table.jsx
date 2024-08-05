import { applyFilters } from '@wordpress/hooks';
import { TableHeader, TableRow } from '@global';

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
	headers,
	rows,
	...props
}) => {
	const defaultClasses = 'tada-table mt-3';

	/**
	 * Filter before the table head
	 *
	 * @action licensehub.before-table-head
	 * @param {array} headers - The headers of the table.
	 * @param {array} rows - The rows of the table.
	 */
	const beforeTableHead = applyFilters(
		'licensehub.before-table-head',
		'',
		headers,
		rows,
	);

	/**
	 * Filter after the table head
	 *
	 * @action licensehub.after-table-head
	 * @param {array} headers - The headers of the table.
	 * @param {array} rows - The rows of the table.
	 */
	const afterTableHead = applyFilters(
		'licensehub.after-table-head',
		'',
		headers,
		rows,
	);

	/**
	 * Filter before the table body
	 *
	 * @action licensehub.before-table-body
	 * @param {array} headers - The headers of the table.
	 * @param {array} rows - The rows of the table.
	 */
	const beforeTableBody = applyFilters(
		'licensehub.before-table-body',
		'',
		headers,
		rows,
	);

	/**
	 * Filter after the table body
	 *
	 * @action licensehub.after-table-body
	 * @param {array} headers - The headers of the table.
	 * @param {array} rows - The rows of the table.
	 */
	const afterTableBody = applyFilters(
		'licensehub.after-table-body',
		'',
		headers,
		rows,
	);

	return (
		<table
			className={className ? defaultClasses + ' ' + className : defaultClasses}>
			{beforeTableHead}
			<thead>
				<tr>
					{headers.map((header, index) => (
						<TableHeader content={header} key={index} />
					))}
				</tr>
			</thead>
			{afterTableHead}
			<tbody>
				{beforeTableBody}
				{rows.map((row, index) => (
					<TableRow
						columns={row}
						key={index}
						editable={editable ? editable : false}
						onBlur={onBlur ? onBlur : false}
						headers={headers}
						{...props}
					/>
				))}
				{afterTableBody}
			</tbody>
		</table>
	);
};
