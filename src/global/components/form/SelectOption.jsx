/**
 * Add an option element for dropdowns
 *
 * @since 1.0.2
 *
 * @param id
 * @param children
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
export const SelectOption = ({ children, ...props }) => {
	return <option {...props}>{children}</option>;
};
