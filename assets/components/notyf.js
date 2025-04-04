export const createNotyf = () => {
  return new Notyf({
    position: {
      x: "right",
      y: "top",
    },
    duration: 2500,
    ripple: false,
  });
};
