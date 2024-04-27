/**
 * Add a submit input styled like a button
 *
 * @since 1.0.2
 *
 * @param className
 * @param loading
 * @param children
 * @param disabled
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
export const Button = ({
	className,
	loading,
	children,
	disabled = false,
	...props
}) => {
	const regularClasses =
		'bg-tadaBlue text-white font-kanit border-0 text-sm mt-4 px-4 py-2 uppercase rounded-lg cursor-pointer transition-all';
	const hoverClasses = 'hover:bg-tadaLighterBlue';
	const disabledColors = loading
		? 'disabled:bg-tadaBlue disabled:text-white'
		: 'disabled:bg-tadaGray disabled:text-black';
	const disabledClasses = `${disabledColors} disabled:cursor-not-allowed`;
	const defaultClasses = `${regularClasses} ${hoverClasses} ${disabledClasses}`;

	return (
		<button
			className={className ? className + ' ' + defaultClasses : defaultClasses}
			disabled={loading ? loading : disabled}
			{...props}>
			{children}
		</button>
	);
};
