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
	const defaultClasses = 'checkbox-container flex flex-row items-center';

	return (
		<label
			className={
				wrapExtraClass ? defaultClasses + ' ' + wrapExtraClass : defaultClasses
			}>
			<span className="checkbox-label font-poppins font-semibold text-tadaBlack text-base block">
				{label}
			</span>
			<input type="checkbox" {...props} />
			<span className="checkmark"></span>
		</label>
	);
};
