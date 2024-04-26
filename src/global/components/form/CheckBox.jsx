/**
 * Add a checkbox input
 *
 * @since 1.0.2
 *
 * @param wrapExtraClass
 * @param label
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
export const CheckBox = ({ wrapExtraClass, label, ...props }) => {
	const defaultClasses = 'checkbox-container tada-flex-row';

	return (
		<label
			className={
				wrapExtraClass ? defaultClasses + ' ' + wrapExtraClass : defaultClasses
			}>
			<span className="checkbox-label">{label}</span>
			<input type="checkbox" {...props} />
			<span className="checkmark"></span>
		</label>
	);
};
