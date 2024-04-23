/**
 * Add a label
 *
 * @since 1.0.2
 *
 * @param children
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
export const Label = ({ children, ...props }) => {
	return <label {...props}>{children}</label>;
};
