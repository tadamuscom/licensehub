import { __ } from '@wordpress/i18n';
import { TableColumn } from '@global/components/table/TableColumn';

export const TableRow = ({ columns, editable, onBlur, onDelete, ...props }) => {
	const renderColumns = () => {
		let returnable = [];

		for (let [columnName, columnData] of Object.entries(columns)) {
			returnable.push(
				<TableColumn
					data={columnData}
					key={Math.random()}
					column={columnName}
					editable={editable}
					onBlur={onBlur}
					{...props}
				/>,
			);
		}

		return returnable;
	};

	return (
		<tr>
			{renderColumns()}
			<td>
				<button
					className="cursor-pointer text-md tada-delete-btn"
					onClick={onDelete}>
					{__('Delete', 'licensehub')}
				</button>
			</td>
		</tr>
	);
};
