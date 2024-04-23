import { TableColumn } from "./TableColumn";
import { __ } from "@wordpress/i18n";

export const TableRow = ({ columns, editable, onBlur, onDelete }) => {
	return (
		<tr>
			{columns.map((column, index) => (
				<TableColumn
					data={value}
					key={index}
					column={column}
					editable={editable}
					onBlur={onBlur}
				/>
			))}
			<td>
				<button className="tada-action-icon tada-delete-btn" onClick={onDelete}>
					{__("Delete", "migratemonkey")}
				</button>
			</td>
		</tr>
	);
};
