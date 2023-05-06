import React, { useState } from "react";

type Teacher = {
  id: string;
  title: string;
  forename: string;
  surname: string;
  classes: any[];
};

type SelectTeacherProps = {
  teachers: Teacher[];
};

function SelectTeacher({ teachers }: SelectTeacherProps) {
  const [selectedTeacher, setSelectedTeacher] = useState("");

  const handleChange = (event: React.ChangeEvent<HTMLSelectElement>) => {
    setSelectedTeacher(event.target.value);
  };

  return (
    <div>
      <label htmlFor="teacher-select">Select Teacher:</label>
      <select
        id="teacher-select"
        value={selectedTeacher}
        onChange={handleChange}
      >
        <option value="">-- Select a teacher --</option>
        {teachers.map((teacher) => (
          <option key={teacher.id} value={teacher.id}>
            {teacher.title} {teacher.forename} {teacher.surname}
          </option>
        ))}
      </select>
    </div>
  );
}

export default SelectTeacher;
